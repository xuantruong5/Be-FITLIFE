<?php

namespace App\Http\Controllers;

use App\Models\ScheduleMember;
use App\Models\TrainerSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleMemberController extends Controller
{
    /**
     * GET /api/trainer/schedule-members?id_schedule={id}
     * HLV xem danh sách hội viên trong một ca học
     */
    public function index(Request $request)
    {
        $request->validate([
            'id_schedule' => 'required|exists:trainer__schedules,id',
        ]);

        $members = ScheduleMember::with(['member', 'package'])
            ->where('id_schedule', $request->id_schedule)
            ->get();

        return response()->json([
            'status'  => true,
            'message' => 'Lấy danh sách hội viên thành công.',
            'data'    => $members,
        ]);
    }

    /**
     * GET /api/member/my-schedules
     * Hội viên xem danh sách lịch đã đăng ký
     */
    public function mySchedules(Request $request)
    {
        $member = Auth::guard('sanctum')->user();

        $registrations = ScheduleMember::with(['schedule.trainer', 'schedule.branch', 'package'])
            ->where('id_member', $member->id)
            ->get();

        return response()->json([
            'status'  => true,
            'message' => 'Lấy danh sách lịch tập đã đăng ký thành công.',
            'data'    => $registrations,
        ]);
    }


    public function destroy(Request $request)
    {
        $id           = $request->route('id');
        $member       = Auth::guard('sanctum')->user();
        $registration = ScheduleMember::where('id_member', $member->id)->find($id);

        if (!$registration) {
            return response()->json([
                'status'  => false,
                'message' => 'Đăng ký không tồn tại hoặc bạn không có quyền.',
            ], 404);
        }

        $schedule = TrainerSchedule::find($registration->id_schedule);
        if ($schedule && $schedule->status != TrainerSchedule::SAP_DIEN_RA) {
            return response()->json([
                'status'  => false,
                'message' => 'Không thể hủy lịch đang/đã diễn ra.',
            ], 400);
        }

        $registration->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Hủy đăng ký lịch tập thành công.',
        ]);
    }
}
