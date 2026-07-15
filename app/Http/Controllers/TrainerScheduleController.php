<?php

namespace App\Http\Controllers;

use App\Http\Requests\TrainerSchedule\RejectTrainerScheduleRequest;
use App\Http\Requests\TrainerSchedule\StoreTrainerScheduleRequest;
use App\Http\Requests\TrainerSchedule\UpdateTrainerScheduleRequest;
use App\Http\Requests\Trainer\ChangeScheduleRequest;
use App\Http\Requests\Trainer\TrainerScheduleRequest;
use App\Models\TrainerSchedule;
use App\Models\scheduleMembers;
use App\Models\Reschedule;
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
    public function StoreSchedule(TrainerScheduleRequest $request)
    {
        $trainer = Auth::guard('sanctum')->user();

        $schedule = TrainerSchedule::create([
            'title'             => $request->title,
            'date'              => $request->date,
            'start_time'        => $request->start_time,
            'end_time'          => $request->end_time,
            'room'              => $request->room,
            'id_package'        => $request->id_package,
            'max_members'       => $request->max_members,
            'current_members'   => 0,
            'approval_status'   => TrainerSchedule::CHUA_DUYET,
            'status'            => TrainerSchedule::SAP_DIEN_RA,
            'id_branch'         => $request->id_branch,
            'id_trainer'        => $trainer->id,
            'admin_note'        => null,
            'note'              => $request->note,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đăng ký lịch thành công, vui lòng chờ quản lý duyệt.',
            'data' => $schedule
        ], 201);
    }

    public function getSchedules(Request $request)
    {
        $trainer = Auth::guard('sanctum')->user();

        if (!$trainer) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy trainer.'
            ], 401);
        }

        $query = TrainerSchedule::with([
            'branch:id,name',
            'scheduleMembers.member',
            'scheduleMembers.attendance',
        ])
        ->where('id_trainer', $trainer->id)
        ->where('approval_status', TrainerSchedule::DA_DUYET);

        // Lọc theo ngày
        if ($request->filled('date')) {

            $query->whereDate('date', $request->date);

        }
        // Lọc theo tháng + năm
        elseif ($request->filled('month') && $request->filled('year')) {

            $query->whereMonth('date', $request->month)
                ->whereYear('date', $request->year);

        }
        // Lọc theo năm
        elseif ($request->filled('year')) {

            $query->whereYear('date', $request->year);

        }
        // Không truyền gì thì lấy hôm nay
        else {

            $query->whereDate('date', today());

        }

        $schedules = $query->orderBy('date')
                        ->orderBy('start_time')
                        ->get();

        return response()->json([
            'success' => true,
            'message' => 'Lấy lịch thành công.',
            'data' => $schedules
        ]);
    }
    public function changeSchedule(ChangeScheduleRequest $request)
    {
        $trainer = Auth::guard('sanctum')->user();

        if (!$trainer) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập.'
            ], 401);
        }

        $schedule = TrainerSchedule::find($request->old_schedule_id);

        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy lịch.'
            ]);
        }

        if ($schedule->id_trainer != $trainer->id) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền đổi lịch này.'
            ],403);
        }

        $reschedule = Reschedule::create([
            'old_schedule_id' => $schedule->id,
            'new_schedule_id' => null,
            'date'            => $request->date,
            'start_time'      => $request->start_time,
            'end_time'        => $request->end_time,
            'reason'          => $request->reason,
            'status'          => Reschedule::CHO_DUYET,
            'request_by'      => Reschedule::TRAINNER,
            'id_member'       => null,
            'id_trainer'      => $trainer->id,
            'trainer_note'    => null,
            'approved_at'     => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đã gửi yêu cầu đổi lịch.',
            'data' => $reschedule
        ]);
    }
    

    public function getTodaySchedules(Request $request)
    {
        // dd($request->all());
        $trainer = Auth::guard('sanctum')->user();

        if (!$trainer) {
            return response()->json([
                'status' => false,
                'message' => 'Vui lòng đăng nhập'
            ], 401);
        }

        $query = TrainerSchedule::with([
            'scheduleMembers.member',
            'scheduleMembers.attendance',
            'package',
            'branch',
        ])
        ->where('id_trainer', $trainer->id)
        ->whereHas('scheduleMembers');

        // Lọc theo ngày
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }
        // Lọc theo tháng + năm
        elseif ($request->filled('month') && $request->filled('year')) {
            $query->whereMonth('date', $request->month)
                ->whereYear('date', $request->year);
        }
        // Lọc theo năm
        elseif ($request->filled('year')) {
            $query->whereYear('date', $request->year);
        }
        // Mặc định là hôm nay
        else {
            $query->whereDate('date', today());
        }

        $data = $query->orderBy('date')
                    ->orderBy('start_time')
                    ->get();

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    










}
