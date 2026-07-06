<?php

namespace App\Http\Controllers;

use App\Models\TrainerSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainerScheduleController extends Controller
{
    /**
     * GET /api/trainer/schedules
     * HLV xem lịch của mình
     */
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

    /**
     * GET /api/admin/schedules
     * Admin xem tất cả lịch
     */
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

    /**
     * POST /api/trainer/schedules
     * HLV tạo lịch mới (chờ admin duyệt)
     */
    public function store(Request $request)
    {
        $trainer = Auth::guard('sanctum')->user();

        $request->validate([
            'title'       => 'required|string|max:255',
            'date'        => 'required|date',
            'start_time'  => 'required',
            'end_time'    => 'required|after:start_time',
            'room'        => 'required|string|max:100',
            'max_members' => 'nullable|integer|min:1',
            'id_branch'   => 'required|exists:branches,id',
            'note'        => 'nullable|string',
        ]);

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

    /**
     * GET /api/trainer/schedules/{id}
     * Xem chi tiết lịch tập
     */
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

    /**
     * PUT /api/trainer/schedules/{id}
     * HLV cập nhật lịch (chỉ khi chưa duyệt)
     */
    public function update(Request $request)
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

        $request->validate([
            'title'       => 'sometimes|required|string|max:255',
            'date'        => 'sometimes|required|date',
            'start_time'  => 'sometimes|required',
            'end_time'    => 'sometimes|required',
            'room'        => 'sometimes|required|string|max:100',
            'max_members' => 'nullable|integer|min:1',
            'note'        => 'nullable|string',
        ]);

        $schedule->update($request->only('title', 'date', 'start_time', 'end_time', 'room', 'max_members', 'note'));
        $schedule->approval_status = TrainerSchedule::CHO_DUYET;
        $schedule->save();

        return response()->json([
            'status'  => true,
            'message' => 'Cập nhật lịch tập thành công.',
            'data'    => $schedule,
        ]);
    }

    /**
     * POST /api/admin/schedules/{id}/approve
     * Admin duyệt lịch
     */
    public function approve(Request $request)
    {
        $id       = $request->route('id');
        $schedule = TrainerSchedule::find($id);

        if (!$schedule) {
            return response()->json(['status' => false, 'message' => 'Lịch không tồn tại.'], 404);
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

    /**
     * POST /api/admin/schedules/{id}/reject
     * Admin từ chối lịch
     */
    public function reject(Request $request)
    {
        $request->validate(['admin_note' => 'required|string']);

        $id       = $request->route('id');
        $schedule = TrainerSchedule::find($id);

        if (!$schedule) {
            return response()->json(['status' => false, 'message' => 'Lịch không tồn tại.'], 404);
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

    /**
     * DELETE /api/trainer/schedules/{id}
     * HLV hủy lịch
     */
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
