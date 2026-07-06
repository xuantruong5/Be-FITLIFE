<?php

namespace App\Http\Controllers;

use App\Models\TrainerNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function store(Request $request)
    {
        $trainer = Auth::guard('sanctum')->user();

        $request->validate([
            'title'       => 'required|string|max:255',
            'id_member'   => 'required|exists:members,id',
            'id_schedule' => 'required|exists:trainer__schedules,id',
            'type'        => 'nullable|string|max:100',
            'priority'    => 'nullable|in:low,normal,high,urgent',
            'content'     => 'required|string',
            'weight'      => 'nullable|numeric',
            'body_fat'    => 'nullable|numeric',
            'muscle'      => 'nullable|numeric',
            'calories'    => 'nullable|integer',
            'status'      => 'nullable|string',
        ]);

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
            return response()->json(['status' => false, 'message' => 'Ghi chú không tồn tại.'], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Lấy thông tin ghi chú thành công.',
            'data'    => $note,
        ]);
    }

    public function update(Request $request)
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

        $request->validate([
            'title'    => 'sometimes|required|string|max:255',
            'type'     => 'nullable|string|max:100',
            'priority' => 'nullable|in:low,normal,high,urgent',
            'content'  => 'sometimes|required|string',
            'weight'   => 'nullable|numeric',
            'body_fat' => 'nullable|numeric',
            'muscle'   => 'nullable|numeric',
            'calories' => 'nullable|integer',
            'status'   => 'nullable|string',
        ]);

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
}
