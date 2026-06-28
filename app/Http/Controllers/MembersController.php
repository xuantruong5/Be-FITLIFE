<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\members;
use App\Models\packages;
use App\Models\Trainer_Schedules;
use App\Models\schedule_members;
use Illuminate\Http\Request;

class MembersController extends Controller
{
   public function registerSchedule(Request $request){
        // Kiểm tra hội viên
        $member = members::find($request->member_id);
        if (!$member) {
            return response()->json([
                'status' => false,
                'message' => 'Hội viên không tồn tại.'
            ], 404);
        }

        // Kiểm tra gói tập
        $package = packages::find($request->package_id);

        if (!$package) {
            return response()->json([
                'status' => false,
                'message' => 'Gói tập không tồn tại.'
            ], 404);
        }

        // Gói ngừng hoạt động
        if ($package->status != packages::HOAT_DONG) {
            return response()->json([
                'status' => false,
                'message' => 'Gói tập đã ngừng hoạt động.'
            ], 400);
        }

        // Gói cơ bản không được đăng ký PT
        if ($package->slug == 'basic') {
            return response()->json([
                'status' => false,
                'message' => 'Gói Cơ Bản không hỗ trợ đăng ký huấn luyện viên.'
            ], 400);
        }

        // Kiểm tra lịch tập
        $schedule = Trainer_Schedules::find($request->trainer_schedule_id);

        if (!$schedule) {
            return response()->json([
                'status' => false,
                'message' => 'Lịch tập không tồn tại.'
            ], 404);
        }

        // Kiểm tra lịch đã được duyệt
        if ($schedule->approval_status != Trainer_Schedules::DA_DUYET) {
            return response()->json([
                'status' => false,
                'message' => 'Lịch tập chưa được duyệt.'
            ], 400);
        }

        // Kiểm tra lịch đã hoàn thành
        if ($schedule->status == Trainer_Schedules::DA_HOAN_THANH) {
            return response()->json([
                'status' => false,
                'message' => 'Lịch tập đã hoàn thành.'
            ], 400);
        }

        // Kiểm tra lịch đã hủy
        if ($schedule->status == Trainer_Schedules::DA_HUY) {
            return response()->json([
                'status' => false,
                'message' => 'Lịch tập đã bị hủy.'
            ], 400);
        }

        // Kiểm tra đã đăng ký chưa
        $isRegister = schedule_members::where('id_member', $member->id)
            ->where('id_schedule', $schedule->id)
            ->exists();

        if ($isRegister) {
            return response()->json([
                'status' => false,
                'message' => 'Bạn đã đăng ký lịch tập này.'
            ], 400);
        }

        // Kiểm tra số lượng hội viên
        $currentMember = schedule_members::where('id_schedule', $schedule->id)
            ->count();

        if ($currentMember >= $schedule->max_members) {
            return response()->json([
                'status' => false,
                'message' => 'Lớp học đã đủ số lượng hội viên.'
            ], 400);
        }

        // Đăng ký lịch tập
        $register = schedule_members::create([
            'id_member'  => $member->id,
            'id_schedule'=> $schedule->id,
            'id_package' => $package->id,
            'id_trainer' => $schedule->id_trainer,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Đăng ký lịch tập thành công.',
            'data' => $register
        ], 201);
    }
}
