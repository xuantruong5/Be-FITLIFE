<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Package;
use App\Models\Attendance;
use App\Models\MemberPackage;
use App\Models\OrderDetail;
use App\Models\DonHang;

use Illuminate\Support\Facades\Route;

class TrainerController extends Controller
{
    public function login(Request $request)
    {
        $check = Auth::guard('trainer')->attempt([
            'email'     => $request->email,
            'password'  => $request->password
        ]);

        if ($check) {
            $trainer   = Auth::guard('trainer')->user();
            $token  = $trainer->createToken('Token_Trainer')->plainTextToken;
            return response()->json([
                'message'   => 'Đăng nhập thành công',
                'status'    => true,
                'token'     => $token,
                'trainer'      => $trainer
            ]);
        } else {
            return response()->json([
                'message'   => 'Đăng nhập thất bại. Vui lòng kiểm tra lại thông tin.',
                'status'    => false
            ]);
        }
    }

    public function logoutTrainer()
    {
        $user = Auth::guard('sanctum')->user();
        if ($user && $user->currentAccessToken()) {
            DB::table('personal_access_tokens')
                ->where('id', $user->currentAccessToken()->id)
                ->delete();
            return response()->json([
                'status'  => true,
                'message' => "Đăng xuất thành công",
            ]);
        }
        else {
            return response()->json([
                'status'  => false,
                'message' => "Có lỗi xảy ra",
            ]);
        }
    }
    public function logoutAllTrainer()
    {
        $user = Auth::guard('sanctum')->user();
        if ($user) {
            $ds_token = $user->tokens;
            foreach ($ds_token as $token) {
                $token->delete();
            }
            return response()->json([
                'status'  => 1,
                'message' => "Đăng xuất thành công",
            ]);
        } else {
            return response()->json([
                'status'  => 0,
                'message' => "Có lỗi xảy ra",
            ]);
        }
    }
    public function getGoiChiNhanh()
    {
        $branches = Branch::select('id', 'name')->get();
        $packages = Package::where('status', Package::HOAT_DONG)
            ->select('id', 'name')
            ->get();

        return response()->json([
            'success' => true,
            'branches' => $branches,
            'packages' => $packages,
        ]);
    }

    public function memberPackages(Request $request)
    {
        $trainer = Auth::guard('sanctum')->user();

        if (!$trainer) {
            return response()->json([
                'status' => false,
                'message' => 'Vui lòng đăng nhập.'
            ], 401);
        }

        $query = MemberPackage::join('members', 'member_packages.id_member', '=', 'members.id')
            ->join('packages', 'member_packages.id_package', '=', 'packages.id')
            ->where('member_packages.id_trainer', $trainer->id)
            ->select(
                'member_packages.*',
                'members.name as member_name',
                'members.avatar',
                'members.phone',
                'packages.name as package_name'
            );

        // Lọc trạng thái gói
        if ($request->filled('status')) {
            $query->where('member_packages.status', $request->status);
        }

        $packages = $query->get();

        foreach ($packages as $item) {

            $present = Attendance::where('id_member', $item->id_member)
                ->where('id_trainer', $trainer->id)
                ->where('status', Attendance::CO_MAT)
                ->count();

            $late = Attendance::where('id_member', $item->id_member)
                ->where('id_trainer', $trainer->id)
                ->where('status', Attendance::DI_TRE)
                ->count();

            $absent = Attendance::where('id_member', $item->id_member)
                ->where('id_trainer', $trainer->id)
                ->where('status', Attendance::VANG)
                ->count();

            // Đã tập = Có mặt + Đi trễ
            $usedSessions = $present + $late;

            $item->present_sessions = $present;
            $item->late_sessions = $late;
            $item->absent_sessions = $absent;

            $item->used_sessions = $usedSessions;
            $item->remaining_sessions = max(0, $item->total_sessions - $usedSessions);

            // Text trạng thái
            switch ($item->status) {
                case MemberPackage::HOAT_DONG:
                    $item->status_text = "Đang hoạt động";
                    break;

                case MemberPackage::HET_HAN:
                    $item->status_text = "Hoàn thành";
                    break;

                case MemberPackage::DA_HUY:
                    $item->status_text = "Đã hủy";
                    break;

                default:
                    $item->status_text = "Chưa duyệt";
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Lấy danh sách học viên thành công.',
            'data' => $packages
        ]);
    }

    public function income(Request $request)
    {
        $trainer = Auth::guard('sanctum')->user();

        if (!$trainer) {
            return response()->json([
                'status' => false,
                'message' => 'Vui lòng đăng nhập.'
            ], 401);
        }

        $month = $request->month ?? date('m');
        $year = $request->year ?? date('Y');

        $orders = DB::table('order_details')
            ->join('don_hangs', 'order_details.id_don_hang', '=', 'don_hangs.id')
            ->where('order_details.id_trainer', $trainer->id)
            ->where('order_details.status', OrderDetail::DA_DUYET)
            ->where('don_hangs.is_thanh_toan', DonHang::DA_THANH_TOAN)
            ->whereMonth('order_details.created_at', $month)
            ->whereYear('order_details.created_at', $year)
            ->get();

        $tongDoanhThu = $orders->sum('total_amount');
         $chart = [
        'T1' => 0,
        'T2' => 0,
        'T3' => 0,
        'T4' => 0,
        'T5' => 0,
    ];

        foreach ($orders as $order) {

            $day = date('d', strtotime($order->created_at));

            if ($day <= 7) {
                $chart['T1'] += $order->total_amount;
            } elseif ($day <= 14) {
                $chart['T2'] += $order->total_amount;
            } elseif ($day <= 21) {
                $chart['T3'] += $order->total_amount;
            } elseif ($day <= 28) {
                $chart['T4'] += $order->total_amount;
            } else {
                $chart['T5'] += $order->total_amount;
            }
        }

        return response()->json([
            'status' => true,
            'tong_don' => $orders->count(),
            'tong_doanh_thu' => $tongDoanhThu,
            'thu_nhap' => $tongDoanhThu,
            'thang' => $month,
            'nam' => $year,

            'chart' => [
                [
                    'week' => 'T1',
                    'income' => $chart['T1']
                ],
                [
                    'week' => 'T2',
                    'income' => $chart['T2']
                ],
                [
                    'week' => 'T3',
                    'income' => $chart['T3']
                ],
                [
                    'week' => 'T4',
                    'income' => $chart['T4']
                ],
                [
                    'week' => 'T5',
                    'income' => $chart['T5']
                ],
            ],

            'chi_tiet' => $orders
        ]);



        // return response()->json([
        //     'status' => true,
        //     'tong_don' => $orders->count(),
        //     'tong_doanh_thu' => $tongDoanhThu,
        //     'thu_nhap' => $tongDoanhThu,
        //     'thang' => $month,
        //     'nam' => $year,
        //     'chi_tiet' => $orders
        // ]);
    }
}
