<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\TrainerSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * GET /api/trainer/attendances?id_schedule={id}
     * Lấy danh sách điểm danh của một buổi tập
     */
    public function index(Request $request)
    {
        $request->validate([
            'id_schedule' => 'required|exists:trainer__schedules,id',
        ]);

        $attendances = Attendance::with(['member'])
            ->where('id_schedule', $request->id_schedule)
            ->get();

        return response()->json([
            'status'  => true,
            'message' => 'Lấy danh sách điểm danh thành công.',
            'data'    => $attendances,
        ]);
    }

    /**
     * POST /api/trainer/attendances
     * HLV điểm danh cho hội viên (bulk)
     */
    public function store(Request $request)
    {
        $trainer = Auth::guard('sanctum')->user();

        $request->validate([
            'id_schedule'             => 'required|exists:trainer__schedules,id',
            'attendances'             => 'required|array',
            'attendances.*.id_member' => 'required|exists:members,id',
            'attendances.*.status'    => 'required|in:0,1,2',
        ]);

        $schedule = TrainerSchedule::find($request->id_schedule);

        if ($schedule->id_trainer != $trainer->id) {
            return response()->json([
                'status'  => false,
                'message' => 'Bạn không có quyền điểm danh buổi học này.',
            ], 403);
        }

        $results = [];
        foreach ($request->attendances as $item) {
            $attendance = Attendance::updateOrCreate(
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
            $results[] = $attendance;
        }

        return response()->json([
            'status'  => true,
            'message' => 'Điểm danh thành công.',
            'data'    => $results,
        ], 201);
    }

    /**
     * GET /api/trainer/attendances/{id}
     * Xem chi tiết điểm danh
     */
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

    /**
     * PUT /api/trainer/attendances/{id}
     * HLV cập nhật trạng thái điểm danh
     */
    public function update(Request $request)
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

        $request->validate([
            'status'         => 'sometimes|required|in:0,1,2',
            'check_in_time'  => 'nullable|date',
            'check_out_time' => 'nullable|date',
        ]);

        $attendance->update($request->only('status', 'check_in_time', 'check_out_time'));

        return response()->json([
            'status'  => true,
            'message' => 'Cập nhật điểm danh thành công.',
            'data'    => $attendance,
        ]);
    }

    /**
     * GET /api/member/my-attendances
     * Hội viên xem lịch sử điểm danh của mình
     */
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
