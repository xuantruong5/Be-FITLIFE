<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Admin\LoginAdminRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use App\Models\DonHang;
use App\Models\Member;
use App\Models\Trainer;
use App\Models\TrainerSchedule;
use App\Models\MemberPackage;
use App\Models\Package;
use App\Models\Reschedule;
use Carbon\Carbon;

class AdminController extends Controller
{
    // public function loginAdmin()
    // {
    //     // Nếu đã đăng nhập, redirect về dashboard
    //     if (Auth::guard('admin')->check()) {
    //         return redirect()->route('admin.dashboard');
    //     }

    //     return view('admin.loginadmin');
    // }
    public function adminLogin(LoginAdminRequest $request)
    {
        // Tìm admin theo email
        $admin = Admin::where('email', $request->email)->first();

        // Kiểm tra admin tồn tại
        if (!$admin) {
            return response()->json([
                'message'   => 'Email không tồn tại trong hệ thống.',
                'status'    => false,
                'errors' => ['email' => ['Email không tồn tại trong hệ thống.']]
            ], 401);
        }

        // Kiểm tra trạng thái active
        if ($admin->status != 1) {
            return response()->json([
                'message'   => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.',
                'status'    => false,
                'errors' => ['email' => ['Tài khoản đã bị khóa.']]
            ], 401);
        }

        // Kiểm tra mật khẩu
        if (!Hash::check($request->password, $admin->password)) {
            return response()->json([
                'message'   => 'Mật khẩu không chính xác.',
                'status'    => false,
                'errors' => ['password' => ['Mật khẩu không chính xác.']]
            ], 401);
        }

        // Đăng nhập
        Auth::guard('admin')->login($admin);

        $admin->update([
            'last_login_at' => now(),
        ]);

        $token = $admin->createToken('Token_Admin')->plainTextToken;

        return response()->json([
            'message'   => 'Đăng nhập thành công',
            'status'    => true,
            'token'     => $token,
            'user'      => $admin
        ]);
    }
    public function adminLogout(Request $request)
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
        } else {
            return response()->json([
                'status'  => false,
                'message' => "Có lỗi xảy ra",
            ]);
        }
    }
    public function checkTokenAdmin()
    {
        $user = Auth::guard('sanctum')->user();
        if ($user && $user instanceof \App\Models\Admin) {
            return response()->json([
                'status' => true,
                'name'    => $user->name,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Bạn cần đăng nhập hệ thống!',
            ]);
        }
    }

    public function index()
    {
        
        $tongDoanhThu = DonHang::where('is_thanh_toan', DonHang::DA_THANH_TOAN)
            ->sum('total_amount');

        $tongMember = Member::where('status', Member::ACTIVE)
            ->count();

        $trainers = Trainer::where('status', Trainer::ACTIVE)
            ->select(
                'id',
                'name',
                'avatar',
                'specialization'
            )
            ->get();
            
        $todaySchedules = TrainerSchedule::join('trainers', 'trainer__schedules.id_trainer', '=', 'trainers.id')
            ->join('branches', 'trainer__schedules.id_branch', '=', 'branches.id')
            ->whereDate('trainer__schedules.date', Carbon::today())
            ->where('trainer__schedules.approval_status', TrainerSchedule::DA_DUYET)
            ->select(
                'trainer__schedules.id',
                'trainer__schedules.title',
                'trainer__schedules.date',
                'trainer__schedules.start_time',
                'trainer__schedules.end_time',
                'trainer__schedules.room',
                'trainer__schedules.current_members',
                'trainer__schedules.max_members',
                'trainers.name as trainer_name',
                'branches.name as branch_name'
            )
            ->orderBy('trainer__schedules.start_time')
            ->get();


            return response()->json([
                'status' => true,
                'data' => [
                    'total_revenue' => $tongDoanhThu,
                    'total_members' => $tongMember,
                    'total_trainers' => $trainers->count(),
                    'trainers' => $trainers,
                    'today_schedules' => $todaySchedules,
                ]
            ]);
        }

    public function getMember(Request $request)
    {
        $query = DB::table('member_packages')
            ->join('members', 'member_packages.id_member', '=', 'members.id')
            ->join('packages', 'member_packages.id_package', '=', 'packages.id')
            ->select(
                'member_packages.id',
                'members.id as member_id',
                'members.name',
                'members.phone',
                'members.avatar',
                'packages.name as package_name',
                'member_packages.price',
                'member_packages.start_date',
                'member_packages.end_date',
                'member_packages.total_sessions',
                'member_packages.used_sessions',
                'member_packages.pt_sessions',
                'member_packages.status'
            );

        // Tìm kiếm theo tên hoặc số điện thoại
        if ($request->filled('keyword')) {
            $query->where(function ($query) use ($request) {
                $query->where('members.name', 'like', '%' . $request->keyword . '%')
                    ->orWhere('members.phone', 'like', '%' . $request->keyword . '%');
            });
        }

        // Lọc trạng thái
        if ($request->filled('status')) {
            $query->where('member_packages.status', $request->status);
        }

        // Lọc gói tập
        if ($request->filled('package_id')) {
            $query->where('member_packages.id_package', $request->package_id);
        }

        $members = $query
            ->orderBy('member_packages.id', 'desc')
            ->paginate(10);

        // Thống kê
        $totalMember = Member::count();

        $activeMember = MemberPackage::where('status', MemberPackage::HOAT_DONG)->count();

        $expireSoon = MemberPackage::where('status', MemberPackage::HOAT_DONG)
            ->whereBetween('end_date', [now(), now()->addDays(7)])
            ->count();

        $expired = MemberPackage::whereDate('end_date', '<', now())
            ->count();

        return response()->json([
            'success' => true,
            'summary' => [
                'total_member' => $totalMember,
                'active_member' => $activeMember,
                'expire_soon' => $expireSoon,
                'expired' => $expired,
            ],
            'members' => $members,
        ]);
    }
    public function getTrainner()
    {
        $today = Carbon::today()->toDateString();
        $now = Carbon::now()->format('H:i:s');

        // Danh sách HLV
        $trainers = Trainer::leftJoin(
                'trainer__schedules',
                'trainers.id',
                '=',
                'trainer__schedules.id_trainer'
            )
            ->select(
                'trainers.id',
                'trainers.name',
                'trainers.phone',
                'trainers.avatar',
                'trainers.specialization',
                'trainers.status',

                DB::raw('COALESCE(SUM(trainer__schedules.current_members),0) as total_members'),

                DB::raw("
                    COUNT(
                        CASE
                            WHEN WEEK(trainer__schedules.date,1) = WEEK(CURDATE(),1)
                            AND YEAR(trainer__schedules.date)=YEAR(CURDATE())
                            THEN trainer__schedules.id
                        END
                    ) as classes_this_week
                ")
            )
            ->groupBy(
                'trainers.id',
                'trainers.name',
                'trainers.phone',
                'trainers.avatar',
                'trainers.specialization',
                'trainers.status'
            )
            ->get();

        $data = $trainers->map(function ($trainer) {

            if ($trainer->status == Trainer::BLOCKED) {
                $trainerStatus = "Nghỉ phép";
            } else {
                $trainerStatus = $trainer->classes_this_week > 0
                    ? "Đang dạy"
                    : "Sẵn sàng";
            }

            return [
                'id' => $trainer->id,
                'avatar' => $trainer->avatar,
                'name' => $trainer->name,
                'phone' => $trainer->phone,
                'specialization' => $trainer->specialization,
                'rating' => 5.0,
                'total_members' => (int)$trainer->total_members,
                'classes_this_week' => (int)$trainer->classes_this_week,
                'status' => $trainerStatus,
            ];
        });

        // Tổng HLV
        $totalTrainer = Trainer::where('status', Trainer::ACTIVE)->count();

        // Lớp hôm nay
        $todayClasses = TrainerSchedule::whereDate('date', $today)
            ->count();

        // Đang lên lớp
        $teachingNow = TrainerSchedule::whereDate('date', $today)
            ->where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->distinct('id_trainer')
            ->count('id_trainer');

        // Đánh giá trung bình (chưa có bảng review)
        $averageRating = 5.0;

        return response()->json([
            'success' => true,

            'summary' => [
                'total_trainers' => $totalTrainer,
                'today_classes' => $todayClasses,
                'average_rating' => $averageRating,
                'teaching_now' => $teachingNow,
            ],

            'data' => $data
        ]);
    }

    public function getPackage(Request $request)
    {
        $query = Package::query();

        // Tìm kiếm theo tên
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Lọc theo thời hạn (1,3,6,12 tháng)
        if ($request->filled('duration') && $request->duration != 'all') {
            $durationDays = (int)$request->duration * 30;

            $query->where('duration_days', $durationDays);
        }

        // Lọc theo loại gói
        if ($request->filled('pt') && $request->pt != 'all') {

            if ($request->pt == 'with-pt') {
                $query->where('description', 'like', '%PT%');
            }

            if ($request->pt == 'no-pt') {
                $query->where('description', 'not like', '%PT%');
            }
        }

        // Lọc trạng thái
        if ($request->filled('status') && $request->status != 'all') {

            if ($request->status == 'active') {
                $query->where('status', Package::HOAT_DONG);
            }

            if ($request->status == 'inactive') {
                $query->where('status', Package::NGUNG_HOAT_DONG);
            }
        }

        $packages = $query
            ->orderByDesc('is_popular')
            ->orderBy('price')
            ->get([
                'id',
                'name',
                'slug',
                'price',
                'duration_days',
                'description',
                'status',
                'is_popular',
                'created_at'
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Lấy danh sách gói tập thành công',
            'data' => $packages
        ]);
    }
   public function getReschedules()
    {
        $reschedules = Reschedule::leftJoin('members', 'reschedules.id_member', '=', 'members.id')
            ->leftJoin('trainers', 'reschedules.id_trainer', '=', 'trainers.id')
            ->select(
                'reschedules.id',
                'reschedules.date',
                'reschedules.start_time',
                'reschedules.end_time',
                'reschedules.reason',
                'reschedules.status',
                'reschedules.request_by',
                'reschedules.trainer_note',
                'reschedules.approved_at',
                'reschedules.created_at',

                'members.name as member_name',
                'members.phone as member_phone',
                'members.avatar as member_avatar',

                'trainers.name as trainer_name',
                'trainers.phone as trainer_phone',
                'trainers.avatar as trainer_avatar'
            )
            ->orderBy('reschedules.status')
            ->orderByDesc('reschedules.created_at')
            ->get();

        $data = $reschedules->map(function ($item) {

            return [
                'id' => $item->id,

                'nguoi_gui' => $item->request_by == Reschedule::MEMBER
                    ? $item->member_name
                    : $item->trainer_name,

                'avatar' => $item->request_by == Reschedule::MEMBER
                    ? $item->member_avatar
                    : $item->trainer_avatar,

                'so_dien_thoai' => $item->request_by == Reschedule::MEMBER
                    ? $item->member_phone
                    : $item->trainer_phone,

                'vai_tro' => $item->request_by == Reschedule::MEMBER
                    ? 'Hội viên'
                    : 'HLV (PT)',

                'lich_moi' => [
                    'date' => $item->date,
                    'start_time' => $item->start_time,
                    'end_time' => $item->end_time,
                ],

                'ly_do' => $item->reason,

                'trang_thai' => $item->status,

                'trainer_note' => $item->trainer_note,

                'approved_at' => $item->approved_at
            ];
        });

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }
   






}
