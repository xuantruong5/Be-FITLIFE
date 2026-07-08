<?php

namespace App\Http\Controllers;

use App\Http\Requests\TrainerSchedule\RejectTrainerScheduleRequest;
use App\Http\Requests\TrainerSchedule\StoreTrainerScheduleRequest;
use App\Http\Requests\TrainerSchedule\UpdateTrainerScheduleRequest;
use App\Models\TrainerSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainerScheduleController extends Controller
{
    public function index(Request $request)
    {
        $trainer   = Auth::guard('sanctum')->user();
        $schedules = TrainerSchedule::with(['branch', 'scheduleMembers.member'])
            ->where('id_trainer', $trainer->id)
            ->orderBy('date', 'desc')
            ->get();

        return response()->json([
            'status'  => true,
            'message' => 'Lấy danh sách lịch tập thành công.',
            'data'    => $schedules,
        ]);
    }

    public function indexAdmin(Request $request)
    {
        $schedules = TrainerSchedule::with(['trainer', 'branch'])
            ->orderBy('date', 'desc')
            ->get();

        return response()->json([
            'status'  => true,
            'message' => 'Lấy danh sách lịch tập thành công.',
            'data'    => $schedules,
        ]);
    }

    public function store(StoreTrainerScheduleRequest $request)
    {
        $trainer = Auth::guard('sanctum')->user();

        $schedule = TrainerSchedule::create([
            'title'           => $request->title,
            'date'            => $request->date,
            'start_time'      => $request->start_time,
            'end_time'        => $request->end_time,
            'room'            => $request->room,
            'max_members'     => $request->max_members,
            'id_branch'       => $request->id_branch,
            'id_trainer'      => $trainer->id,
            'note'            => $request->note,
            'approval_status' => TrainerSchedule::CHO_DUYET,
            'status'          => TrainerSchedule::SAP_DIEN_RA,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Tạo lịch tập thành công. Chờ admin duyệt.',
            'data'    => $schedule->load('branch'),
        ], 201);
    }

    public function show(Request $request)
    {
        $id       = $request->route('id');
        $schedule = TrainerSchedule::with(['trainer', 'branch', 'scheduleMembers.member', 'attendances'])->find($id);

        if (!$schedule) {
            return response()->json([
                'status'  => false,
                'message' => 'Lịch tập không tồn tại.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Lấy thông tin lịch tập thành công.',
            'data'    => $schedule,
        ]);
    }

    public function update(UpdateTrainerScheduleRequest $request)
    {
        $id      = $request->route('id');
        $trainer = Auth::guard('sanctum')->user();

        $schedule = TrainerSchedule::where('id_trainer', $trainer->id)->find($id);

        if (!$schedule) {
            return response()->json([
                'status'  => false,
                'message' => 'Lịch tập không tồn tại hoặc bạn không có quyền.',
            ], 404);
        }

        if ($schedule->approval_status == TrainerSchedule::DA_DUYET) {
            return response()->json([
                'status'  => false,
                'message' => 'Lịch đã được duyệt, không thể chỉnh sửa.',
            ], 400);
        }

        $schedule->update($request->only('title', 'date', 'start_time', 'end_time', 'room', 'max_members', 'note'));
        $schedule->approval_status = TrainerSchedule::CHO_DUYET;
        $schedule->save();

        return response()->json([
            'status'  => true,
            'message' => 'Cập nhật lịch tập thành công.',
            'data'    => $schedule,
        ]);
    }

    public function approve(Request $request)
    {
        $id       = $request->route('id');
        $schedule = TrainerSchedule::find($id);

        if (!$schedule) {
            return response()->json([
                'status'  => false,
                'message' => 'Lịch không tồn tại.',
            ], 404);
        }

        $schedule->update([
            'approval_status' => TrainerSchedule::DA_DUYET,
            'admin_note'      => $request->admin_note,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Đã duyệt lịch tập.',
            'data'    => $schedule,
        ]);
    }

    public function reject(RejectTrainerScheduleRequest $request)
    {
        $id       = $request->route('id');
        $schedule = TrainerSchedule::find($id);

        if (!$schedule) {
            return response()->json([
                'status'  => false,
                'message' => 'Lịch không tồn tại.',
            ], 404);
        }

        $schedule->update([
            'approval_status' => TrainerSchedule::TU_CHOI,
            'admin_note'      => $request->admin_note,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Đã từ chối lịch tập.',
            'data'    => $schedule,
        ]);
    }

    public function destroy(Request $request)
    {
        $id      = $request->route('id');
        $trainer = Auth::guard('sanctum')->user();

        $schedule = TrainerSchedule::where('id_trainer', $trainer->id)->find($id);

        if (!$schedule) {
            return response()->json([
                'status'  => false,
                'message' => 'Lịch không tồn tại hoặc bạn không có quyền.',
            ], 404);
        }

        $schedule->update(['status' => TrainerSchedule::DA_HUY]);

        return response()->json([
            'status'  => true,
            'message' => 'Đã hủy lịch tập.',
        ]);
    }
}
