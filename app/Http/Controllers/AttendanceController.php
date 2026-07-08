<?php

namespace App\Http\Controllers;

use App\Http\Requests\Attendance\IndexAttendanceRequest;
use App\Http\Requests\Attendance\StoreAttendanceRequest;
use App\Http\Requests\Attendance\UpdateAttendanceRequest;
use App\Models\Attendance;
use App\Models\TrainerSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index(IndexAttendanceRequest $request)
    {
        $attendances = Attendance::with(['member'])
            ->where('id_schedule', $request->id_schedule)
            ->get();

        return response()->json([
            'status'  => true,
            'message' => 'Lấy danh sách điểm danh thành công.',
            'data'    => $attendances,
        ]);
    }

    public function store(StoreAttendanceRequest $request)
    {
        $trainer  = Auth::guard('sanctum')->user();
        $schedule = TrainerSchedule::find($request->id_schedule);

        if ($schedule->id_trainer != $trainer->id) {
            return response()->json([
                'status'  => false,
                'message' => 'Bạn không có quyền điểm danh buổi học này.',
            ], 403);
        }

        $results = [];
        foreach ($request->attendances as $item) {
            $results[] = Attendance::updateOrCreate(
                [
                    'id_schedule' => $request->id_schedule,
                    'id_member'   => $item['id_member'],
                ],
                [
                    'id_trainer'     => $trainer->id,
                    'status'         => $item['status'],
                    'check_in_time'  => $item['check_in_time']  ?? now(),
                    'check_out_time' => $item['check_out_time'] ?? null,
                ]
            );
        }

        return response()->json([
            'status'  => true,
            'message' => 'Điểm danh thành công.',
            'data'    => $results,
        ], 201);
    }

    public function show(Request $request)
    {
        $id         = $request->route('id');
        $attendance = Attendance::with(['member', 'trainer', 'schedule'])->find($id);

        if (!$attendance) {
            return response()->json([
                'status'  => false,
                'message' => 'Không tìm thấy dữ liệu điểm danh.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Lấy thông tin điểm danh thành công.',
            'data'    => $attendance,
        ]);
    }

    public function update(UpdateAttendanceRequest $request)
    {
        $id         = $request->route('id');
        $trainer    = Auth::guard('sanctum')->user();
        $attendance = Attendance::where('id_trainer', $trainer->id)->find($id);

        if (!$attendance) {
            return response()->json([
                'status'  => false,
                'message' => 'Không tìm thấy dữ liệu điểm danh hoặc bạn không có quyền.',
            ], 404);
        }

        $attendance->update($request->only('status', 'check_in_time', 'check_out_time'));

        return response()->json([
            'status'  => true,
            'message' => 'Cập nhật điểm danh thành công.',
            'data'    => $attendance,
        ]);
    }

    public function myAttendances(Request $request)
    {
        $member      = Auth::guard('sanctum')->user();
        $attendances = Attendance::with(['schedule.trainer', 'schedule.branch'])
            ->where('id_member', $member->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status'  => true,
            'message' => 'Lấy lịch sử điểm danh thành công.',
            'data'    => $attendances,
        ]);
    }
}
