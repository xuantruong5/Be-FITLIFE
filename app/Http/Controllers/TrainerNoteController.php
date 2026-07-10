<?php

namespace App\Http\Controllers;

use App\Http\Requests\TrainerNote\StoreTrainerNoteRequest;
use App\Http\Requests\TrainerNote\UpdateTrainerNoteRequest;
use App\Models\TrainerNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TrainerNoteController extends Controller
{
    public function index(Request $request)
    {
        $trainer = Auth::guard('sanctum')->user();

        $query = TrainerNote::with(['member', 'schedule'])
            ->where('id_trainer', $trainer->id);

        if ($request->id_member) {
            $query->where('id_member', $request->id_member);
        }
        if ($request->id_schedule) {
            $query->where('id_schedule', $request->id_schedule);
        }

        $notes = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'status'  => true,
            'message' => 'Lấy danh sách ghi chú thành công.',
            'data'    => $notes,
        ]);
    }

    public function myNotes(Request $request)
    {
        $member = Auth::guard('sanctum')->user();

        $notes = TrainerNote::with(['trainer', 'schedule'])
            ->where('id_member', $member->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status'  => true,
            'message' => 'Lấy ghi chú sức khỏe thành công.',
            'data'    => $notes,
        ]);
    }

    public function store(StoreTrainerNoteRequest $request)
    {
        $trainer = Auth::guard('sanctum')->user();

        $note = TrainerNote::create(array_merge(
            $request->only('title', 'type', 'priority', 'content', 'weight', 'body_fat', 'muscle', 'calories', 'status', 'id_member', 'id_schedule'),
            ['id_trainer' => $trainer->id]
        ));

        return response()->json([
            'status'  => true,
            'message' => 'Tạo ghi chú thành công.',
            'data'    => $note->load(['member', 'schedule']),
        ], 201);
    }

    public function show(Request $request)
    {
        $id   = $request->route('id');
        $note = TrainerNote::with(['trainer', 'member', 'schedule'])->find($id);

        if (!$note) {
            return response()->json([
                'status'  => false,
                'message' => 'Ghi chú không tồn tại.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Lấy thông tin ghi chú thành công.',
            'data'    => $note,
        ]);
    }

    public function update(UpdateTrainerNoteRequest $request)
    {
        $id      = $request->route('id');
        $trainer = Auth::guard('sanctum')->user();
        $note    = TrainerNote::where('id_trainer', $trainer->id)->find($id);

        if (!$note) {
            return response()->json([
                'status'  => false,
                'message' => 'Ghi chú không tồn tại hoặc bạn không có quyền.',
            ], 404);
        }

        $note->update($request->only('title', 'type', 'priority', 'content', 'weight', 'body_fat', 'muscle', 'calories', 'status'));

        return response()->json([
            'status'  => true,
            'message' => 'Cập nhật ghi chú thành công.',
            'data'    => $note,
        ]);
    }

    public function destroy(Request $request)
    {
        $id      = $request->route('id');
        $trainer = Auth::guard('sanctum')->user();
        $note    = TrainerNote::where('id_trainer', $trainer->id)->find($id);

        if (!$note) {
            return response()->json([
                'status'  => false,
                'message' => 'Ghi chú không tồn tại hoặc bạn không có quyền.',
            ], 404);
        }

        $note->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Xóa ghi chú thành công.',
        ]);
    }










    

    public function myTrainerNote()
    {
        $member = Auth::guard('sanctum')->user();

        $data = TrainerNote::join('trainers', 'trainer_notes.id_trainer', '=', 'trainers.id')
            ->where('trainer_notes.id_member', $member->id)
            ->select(
                'trainer_notes.id',
                'trainer_notes.title',
                'trainer_notes.category',
                'trainer_notes.note',
                'trainers.name as trainer_name',
                // 'trainers.avtar', tai vi dang null
                DB::raw("DATE_FORMAT(trainer_notes.created_at, '%d/%m/%Y') as created_date"),
                DB::raw("
                    CASE trainer_notes.category
                        WHEN 'Kỹ thuật' THEN '#4FC3F7'
                        WHEN 'Dinh dưỡng' THEN '#FFA34D'
                        WHEN 'Phục hồi' THEN '#4CD964'
                        WHEN 'Mục tiêu' THEN '#56D97C'
                        ELSE '#999999'
                    END AS color
                ")
            )
            ->orderBy('trainer_notes.created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Lấy danh sách ghi chú thành công.',
            'data' => $data,
        ], 200);
    }


}
