<?php

namespace App\Http\Controllers;

use App\Models\Reschedule;
use App\Models\TrainerSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RescheduleController extends Controller
{

    public function index(Request $request)
    {
        $trainer     = Auth::guard('sanctum')->user();
        $reschedules = Reschedule::with(['member', 'schedule'])
            ->where('id_trainer', $trainer->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status'  => true,
            'message' => 'Lấy danh sách yêu cầu đổi lịch thành công.',
            'data'    => $reschedules,
        ]);
    }

    public function myReschedules(Request $request)
    {
        $member      = Auth::guard('sanctum')->user();
        $reschedules = Reschedule::with(['schedule.trainer', 'schedule.branch'])
            ->where('id_member', $member->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status'  => true,
            'message' => 'Lấy danh sách yêu cầu đổi lịch thành công.',
            'data'    => $reschedules,
        ]);
    }

    public function store(Request $request)
    {
        $member = Auth::guard('sanctum')->user();

        $request->validate([
            'id_schedule' => 'required|exists:trainer__schedules,id',
            'date'        => 'required|date|after:today',
            'start_time'  => 'required',
            'end_time'    => 'required|after:start_time',
            'reason'      => 'required|string|max:500',
        ]);

        $schedule = TrainerSchedule::find($request->id_schedule);

        if (!$schedule) {
            return response()->json(['status' => false, 'message' => 'Lịch tập không tồn tại.'], 404);
        }

        $pending = Reschedule::where('id_member', $member->id)
            ->where('id_schedule', $request->id_schedule)
            ->where('status', Reschedule::CHO_DUYET)
            ->exists();

        if ($pending) {
            return response()->json([
                'status'  => false,
                'message' => 'Bạn đã có yêu cầu đổi lịch đang chờ duyệt.',
            ], 400);
        }

        $reschedule = Reschedule::create([
            'id_member'   => $member->id,
            'id_schedule' => $request->id_schedule,
            'id_trainer'  => $schedule->id_trainer,
            'date'        => $request->date,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
            'reason'      => $request->reason,
            'status'      => Reschedule::CHO_DUYET,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Gửi yêu cầu đổi lịch thành công.',
            'data'    => $reschedule,
        ], 201);
    }

    /**
     * GET /api/trainer/reschedules/{id}
     * Xem chi tiết yêu cầu
     */
    public function show(Request $request)
    {
        $id         = $request->route('id');
        $reschedule = Reschedule::with(['member', 'trainer', 'schedule'])->find($id);

        if (!$reschedule) {
            return response()->json(['status' => false, 'message' => 'Yêu cầu không tồn tại.'], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Lấy thông tin yêu cầu thành công.',
            'data'    => $reschedule,
        ]);
    }

    /**
     * POST /api/trainer/reschedules/{id}/approve
     * HLV duyệt yêu cầu đổi lịch
     */
    public function approve(Request $request)
    {
        $id         = $request->route('id');
        $trainer    = Auth::guard('sanctum')->user();
        $reschedule = Reschedule::where('id_trainer', $trainer->id)->find($id);

        if (!$reschedule) {
            return response()->json(['status' => false, 'message' => 'Yêu cầu không tồn tại.'], 404);
        }

        $reschedule->update([
            'status'       => Reschedule::DA_DUYET,
            'trainer_note' => $request->trainer_note,
            'approved_at'  => now(),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Đã duyệt yêu cầu đổi lịch.',
            'data'    => $reschedule,
        ]);
    }

    /**
     * POST /api/trainer/reschedules/{id}/reject
     * HLV từ chối yêu cầu đổi lịch
     */
    public function reject(Request $request)
    {
        $request->validate(['trainer_note' => 'required|string']);

        $id         = $request->route('id');
        $trainer    = Auth::guard('sanctum')->user();
        $reschedule = Reschedule::where('id_trainer', $trainer->id)->find($id);

        if (!$reschedule) {
            return response()->json(['status' => false, 'message' => 'Yêu cầu không tồn tại.'], 404);
        }

        $reschedule->update([
            'status'       => Reschedule::TU_CHOI,
            'trainer_note' => $request->trainer_note,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Đã từ chối yêu cầu đổi lịch.',
            'data'    => $reschedule,
        ]);
    }
}
