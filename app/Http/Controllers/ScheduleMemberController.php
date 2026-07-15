<?php

namespace App\Http\Controllers;

use App\Models\ScheduleMember;
use App\Models\TrainerSchedule;
use App\Models\MemberPackage;
use App\Models\OrderDetail;
use App\Models\DonHang;
use App\Models\Reschedule;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Member\CancelScheduleRequest;
use App\Http\Requests\Member\ChangeScheduleRequest;

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
       
        $data = ScheduleMember::join('order_details', 'schedule_members.id_order_detail', '=', 'order_details.id')
        ->join('don_hangs', 'order_details.id_don_hang', '=', 'don_hangs.id')
        ->where('schedule_members.id_member', $member->id)
        ->where('don_hangs.is_thanh_toan', DonHang::DA_THANH_TOAN)
        ->select(
            'schedule_members.id',
            'order_details.schedule_title as title',
            'order_details.start_time',
            'order_details.end_time',
            'order_details.room',
            'order_details.trainer_name',
            'order_details.package_name',
            'order_details.branch_name',
            DB::raw("DATE_FORMAT(order_details.schedule_date, '%d/%m/%Y') as created_date"),

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
                    WHEN 0 THEN '#4FC3F7'
                    WHEN 1 THEN '#4FC3F7'
                    WHEN 2 THEN '#4CD964'
                    WHEN 3 THEN '#FF3B30'
                    ELSE '#999999'
                END AS status_color
            ")
        )
        ->orderBy('order_details.schedule_date')
        ->orderBy('order_details.start_time')
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
            ->join('order_details', 'schedule_members.id_order_detail', '=', 'order_details.id')
            ->where('order_details.status', OrderDetail::DA_DUYET)
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
    public function cancelSchedule(CancelScheduleRequest $request)
    {
        $member = Auth::guard('sanctum')->user();

        $schedule = ScheduleMember::where('id', $request->schedule_member_id)
            ->where('id_member', $member->id)
            ->first();

        if (!$schedule) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy lịch tập.'
            ], 404);
        }

        if ($schedule->status == ScheduleMember::CHECK_IN) {
            return response()->json([
                'status' => false,
                'message' => 'Không thể hủy vì đã check-in.'
            ], 400);
        }

        if ($schedule->status == ScheduleMember::HOAN_THANH) {
            return response()->json([
                'status' => false,
                'message' => 'Buổi tập đã hoàn thành.'
            ], 400);
        }

        if ($schedule->status == ScheduleMember::DA_HUY) {
            return response()->json([
                'status' => false,
                'message' => 'Lịch tập đã được hủy trước đó.'
            ], 400);
        }

        $schedule->status = ScheduleMember::DA_HUY;
        $schedule->cancel_reason = $request->cancel_reason;
        $schedule->save();

        // $memberPackage = MemberPackage::where('id_member', $member->id)
        //     ->where('id_package', $schedule->id_package)
        //     ->first();

        // if ($memberPackage) {
        //     $memberPackage->increment('remaining_sessions');
        // }

        // Giảm số người của lịch PT
        TrainerSchedule::where('id', $schedule->id_trainer_schedule)
            ->decrement('current_members');

        return response()->json([
            'status' => true,
            'message' => 'Hủy lịch tập thành công.'
        ]);
    }


    public function changeSchedule(ChangeScheduleRequest $request)
    {
        $member = Auth::guard('sanctum')->user();
        if (!$member) {
            return response()->json([
                'status' => false,
                'message' => 'Vui lòng đăng nhập'
            ], 401);
        }
        $scheduleMember = ScheduleMember::where('id_schedule', $request->id_schedule)
            ->where('id_member', $member->id)
            ->first();
        if (!$scheduleMember) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy buổi tập'
            ], 404);
        }
        $currentSchedule = TrainerSchedule::find($scheduleMember->id_trainer_schedule);
        if (!$currentSchedule) {
                return response()->json([
                    'status' => false,
                    'message' => 'Không tìm thấy lịch của PT'
                ], 404);
            }  


        $newSchedule = TrainerSchedule::where('date', $request->date)
            ->where('start_time', $request->start_time)
            ->where('end_time', $request->end_time)
            ->where('id_trainer', $currentSchedule->id_trainer)
            ->first();
        if (!$newSchedule) {
            return response()->json([
                'status' => false,
                'message' => 'Không có lịch tập phù hợp'
            ], 404);
        }
       $exists = Reschedule::where('old_schedule_id', $scheduleMember->id_schedule)
            ->where('id_member', $member->id)
            ->where('status', Reschedule::CHO_DUYET)
            ->first();
        if ($exists) {
            return response()->json([
                'status' => false,
                'message' => 'Bạn đã gửi yêu cầu đổi lịch, vui lòng chờ duyệt'
            ]);
        }
        $reschedule = Reschedule::create([
            'old_schedule_id' => $scheduleMember->id_schedule,
            'new_schedule_id' => $newSchedule->id,
            // 'id_schedule' => $newSchedule->id, // lịch mới
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'reason' => $request->reason,
            'status' => Reschedule::CHO_DUYET,
            'request_by' => Reschedule::MEMBER,
            'id_member' => $member->id,
            'id_trainer' => $newSchedule->id_trainer,
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Đã gửi yêu cầu đổi lịch, vui lòng chờ duyệt',
            'data' => $reschedule
        ], 201);
    }
    public function getChangeSchedule($id)
    {
        $member = Auth::guard('sanctum')->user();

        if(!$member){
            return response()->json([
                'status'=>false,
                'message'=>'Vui lòng đăng nhập'
            ],401);
        }
        $schedule = ScheduleMember::join( 'trainer__schedules', 'schedule_members.id_schedule', '=', 'trainer__schedules.id' )
            ->join('trainers','trainer__schedules.id_trainer','=','trainers.id')
            ->where('schedule_members.id',$id)
            ->where('schedule_members.id_member',$member->id)
            ->select(
                'trainer__schedules.id as trainer_schedule_id',
                'schedule_members.id as schedule_member_id',
                'trainer__schedules.id_trainer',
                DB::raw("DATE_FORMAT(trainer__schedules.date, '%d/%m/%Y') as date"),
                'trainer__schedules.start_time',
                'trainer__schedules.end_time',
                'trainer__schedules.room',
                'trainer__schedules.title',
                'trainers.name as trainer_name'
            )
            ->first();
        if(!$schedule){
            return response()->json([
                'status'=>false,
                'message'=>'Không tìm thấy lịch tập'
            ]);
        }
            $trainerSchedules = TrainerSchedule::where('id_trainer', $schedule->id_trainer)
            ->where('approval_status', TrainerSchedule::DA_DUYET)
            ->where('status', TrainerSchedule::SAP_DIEN_RA)
            ->whereDate('date', '>=', now()->toDateString())
            ->whereColumn('current_members', '<', 'max_members')
            ->orderBy('date')
            ->orderBy('start_time')
            ->get([
                'id',
                'date',
                'start_time',
                'end_time'
            ]);

        return response()->json([
            'status' => true,
            'data' => [
                'id' => $schedule->schedule_member_id,
                'trainer_schedule_id' => $schedule->trainer_schedule_id,
                'trainer_name' => $schedule->trainer_name,
                'title' => $schedule->title,
                'room' => $schedule->room,
                'date' => $schedule->date,
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
                'trainer_schedules' => $trainerSchedules
            ]
        ]);
    }
   

















}
