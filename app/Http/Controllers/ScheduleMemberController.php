<?php

namespace App\Http\Controllers;

use App\Models\ScheduleMember;
use App\Models\TrainerSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function mySchedule()
    {
        $member = Auth::guard('sanctum')->user();
        $data = ScheduleMember::join( 'trainer__schedules', 'schedule_members.id_schedule', '=', 'trainer__schedules.id')
            ->join( 'trainers', 'trainer__schedules.id_trainer', '=', 'trainers.id')
            ->join( 'packages',  'schedule_members.id_package', '=', 'packages.id')
            ->join('branches', 'trainer__schedules.id_branch', '=', 'branches.id')
            ->where('schedule_members.id_member', $member->id)
            ->select('schedule_members.id', 'trainer__schedules.title',    'trainer__schedules.start_time', 
            'trainer__schedules.end_time', 'trainer__schedules.room','trainers.name as trainer_name','packages.name as package_name',
            'branches.name as branch_name',  'branches.address as branch_address', 'branches.phone as branch_phone',
            DB::raw("DATE_FORMAT(trainer__schedules.date, '%d/%m/%Y') as created_date"),
                // // Các thông tin khác
                // 'schedule_members.checked_in_at',
                // 'schedule_members.trainer_note',
                // 'schedule_members.cancel_reason',
                DB::raw("
                    CASE schedule_members.status
                        WHEN 0 THEN 'Sắp tới'
                        WHEN 1 THEN 'Check-in'
                        WHEN 2 THEN 'Hoàn thành'
                        WHEN 3 THEN 'Đã hủy'
                    END AS status_text
                "),
                DB::raw("
                    CASE schedule_members.status
                        WHEN 0 THEN '#4FC3F7'  -- Sắp tới (cam)
                        WHEN 1 THEN '#4FC3F7'  -- Check-in (xanh dương)
                        WHEN 2 THEN '#4CD964'  -- Hoàn thành (xanh lá)
                        WHEN 3 THEN '#FF3B30'  -- Đã hủy (đỏ)
                        ELSE '#999999'
                    END AS status_color
                ")
            )
            ->orderBy('trainer__schedules.date')
            ->orderBy('trainer__schedules.start_time')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Lấy lịch tập thành công.',
            'data' => $data,
        ]);
    }

    public function scheduleDetail($id)
    {
        $member = Auth::guard('sanctum')->user();
        $data = ScheduleMember::join( 'trainer__schedules', 'schedule_members.id_schedule', '=', 'trainer__schedules.id')
            ->join( 'trainers', 'trainer__schedules.id_trainer', '=', 'trainers.id')
            ->join( 'packages',  'schedule_members.id_package', '=', 'packages.id')
            ->join('branches', 'trainer__schedules.id_branch', '=', 'branches.id')
            ->where('schedule_members.id_member', $member->id)
            ->where('schedule_members.id', $id)
            ->select('schedule_members.id', 'trainer__schedules.title',    'trainer__schedules.start_time', 
            'trainer__schedules.end_time', 'trainer__schedules.room','trainers.name as trainer_name','packages.name as package_name',
            'branches.name as branch_name',  'branches.address as branch_address', 'branches.phone as branch_phone',
            DB::raw("DATE_FORMAT(trainer__schedules.date, '%d/%m/%Y') as created_date"),
                // // Các thông tin khác
                // 'schedule_members.checked_in_at',
                // 'schedule_members.trainer_note',
                // 'schedule_members.cancel_reason',
                DB::raw("
                    CASE schedule_members.status
                        WHEN 0 THEN 'Sắp tới'
                        WHEN 1 THEN 'Check-in'
                        WHEN 2 THEN 'Hoàn thành'
                        WHEN 3 THEN 'Đã hủy'
                    END AS status_text
                "),
                DB::raw("
                    CASE schedule_members.status
                        WHEN 0 THEN '#4FC3F7'  -- Sắp tới (cam)
                        WHEN 1 THEN '#4FC3F7'  -- Check-in (xanh dương)
                        WHEN 2 THEN '#4CD964'  -- Hoàn thành (xanh lá)
                        WHEN 3 THEN '#FF3B30'  -- Đã hủy (đỏ)
                        ELSE '#999999'
                    END AS status_color
                ")
            )
            ->orderBy('trainer__schedules.date')
            ->orderBy('trainer__schedules.start_time')
            ->first();

        return response()->json([
            'status' => true,
            'message' => 'Lấy lịch tập thành công.',
            'data' => $data,
        ]);
    }




}
