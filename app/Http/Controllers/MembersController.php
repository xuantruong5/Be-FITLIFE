<?php

namespace App\Http\Controllers;
use App\Http\Requests\Member\RegisterMemberRequest;
use App\Http\Requests\Member\MemberUpdateProfileRequest;
use App\Http\Requests\Member\CreateOrderRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MemberPackage;
use App\Models\ScheduleMember;
use App\Models\Package;
use App\Models\Trainer;
use App\Models\DonHang;
use App\Models\Promotion;
use App\Models\OrderDetail;
use App\Models\TrainerSchedule;
use App\Models\Trainer_Schedules;
use App\Models\schedule_members;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\MasterMail;
use Carbon\Carbon;



class MembersController extends Controller
{
    public function register(RegisterMemberRequest $request)
    {
        $data = $request->all();
        $member = Member::create([
            'name'          => $data['name'],
            'email'         => $data['email'],
            'phone'         => $data['phone'],
            'password'      => bcrypt($data['password']),
        ]);
        Mail::to($data['email'])->send(new MasterMail($member));

        return response()->json([
            'message'   => 'Đăng ký thành công',
            'status'    => true
        ]);
    }





    public function registerSchedule(Request $request)
    {
        // $user   = Auth::guard('sanctum')->user();
        //     if ($user == null) {
        //         return response()->json([
        //             'message' => 'Bạn chưa đăng nhập',
        //             'status' => false
        //         ]);
        //     }
         // Kiểm tra hội viên
        $member = Member::find($request->member_id);
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


    public function login(Request $request)
    {
        $check = Auth::guard('member')->attempt([
            'email'     => $request->email,
            'password'  => $request->password
        ]);
        if ($check) {
            $user   = Auth::guard('member')->user();
            $token  = $user->createToken('Token_Member')->plainTextToken;
            return response()->json([
                'message'   => 'Đăng nhập thành công',
                'status'    => true,
                'token'     => $token,
                'user'      => $user
            ]);
        } else {
            return response()->json([
                'message'   => 'Đăng nhập thất bại. Vui lòng kiểm tra lại thông tin.',
                'status'    => false
            ]);
        }
    }
    public function loginGoogle(Request $request)
    {
        $data = $request->all();

        $member= Member::where('email', $data['email'])->first();
        if($member){

            $token  = $member->createToken('Token_Member')->plainTextToken;

            return  response()->json([
                'message'   => 'Đăng nhập Google thành công',
                'status'    => true,
                'token'     => $token,
                'member'      => $member
            ]);
        }

        $member = Member::create([
            'name'    => $data['name'],
            'email'     => $data['email'],
            'password'  => bcrypt('123456'),
            'avatar'    => $data['photo']
        ]);

        $token = $member->createToken('Token_Member')->plainTextToken;

        return response()->json([
            'message'   => 'Đăng nhập Google thành công',
            'status'    => true,
            'token'     => $token,
            'member'      => $member
        ]);


    }

    public function logoutMember(Request $request)
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
    public function logoutAllMember()
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
    public function getMember(Request $request)
    {
        $member = Member::all();
        return response()->json([
            'message' => 'Lấy dữ liệu Member thành công',
            'status' => true,
            'data' => $member,
        ]);
    }

    public function changProfile(MemberUpdateProfileRequest $request)
    {
        $member = Auth::guard('sanctum')->user();
        $member->update([
            'name'          => $request->name,
            'phone'         => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'gender'        => $request->gender,
            'address'       => $request->address,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Đã cập nhật thông tin thành công.',
            'data' => $member
        ]);

    }
    public function myPackage()
    {
        $member = Auth::guard('sanctum')->user();
        $data = MemberPackage::join('packages', 'member_packages.id_package', '=', 'packages.id')
        ->join('order_details', function ($join) {
                        $join->on('member_packages.id_member', '=', 'order_details.id_member')
                            ->on('member_packages.id_package', '=', 'order_details.id_package');
                    })           
        ->where('member_packages.id_member', $member->id)
        ->where('order_details.status', OrderDetail::DA_DUYET)
        ->select(
            'member_packages.*',
            'packages.name as package_name',
            'packages.slug',
            'packages.duration_days',
            DB::raw("
                CASE member_packages.status
                    WHEN 0 THEN 'Hết hạn'
                    WHEN 1 THEN 'Đang hoạt động'
                    WHEN 2 THEN 'Chưa kích hoạt'
                    WHEN 3 THEN 'Đã hủy'
                END AS status_text
            ")
        )
        ->get();
        return response()->json([
            'status' => true,
            'message' => 'Lấy danh sách gói tập thành công.',
            'data' => $data,
        ], 200);
        
    }
    


    public function getTrainer()
    {
        $data = Trainer::join('trainer__schedules', 'trainers.id', '=', 'trainer__schedules.id_trainer')
                        ->where('trainer__schedules.approval_status', TrainerSchedule::DA_DUYET)
                        ->select(  'trainers.id',  'trainers.name',  'trainers.avatar',
                                    DB::raw("DATE_FORMAT(trainers.date_of_birth, '%d/%m/%Y') as date_of_birth"),
                                    DB::raw("DATE_FORMAT(trainer__schedules.date, '%d/%m/%Y') as date"),
                                    'trainers.address', 'trainers.specialization', 'trainers.experience', 'trainer__schedules.title as schedule',
                                    DB::raw("
                                        CONCAT(
                                            TIME_FORMAT(trainer__schedules.start_time, '%H:%i'),
                                            ' - ',
                                            TIME_FORMAT(trainer__schedules.end_time, '%H:%i')
                                        ) as training_time
                                    "),
                                    'trainer__schedules.current_members as total_students',
                                    'trainer__schedules.note',
                                    DB::raw("4.8 as rating"),
                                    DB::raw("
                                        CASE DAYOFWEEK(trainer__schedules.date)
                                            WHEN 2 THEN 'T2'
                                            WHEN 3 THEN 'T3'
                                            WHEN 4 THEN 'T4'
                                            WHEN 5 THEN 'T5'
                                            WHEN 6 THEN 'T6'
                                            WHEN 7 THEN 'T7'
                                            WHEN 1 THEN 'CN'
                                        END as active_day
                                    ")
                                )
        ->get();
        return response()->json([
            'status' => true,
            'message' => 'Lấy thông tin huấn luyện viên thành công.',
            'data' => $data,
        ], 200);
    }

    public function getTrainerByPackage($id_package)
    {
        $data = Trainer::join('package_trainers', 'trainers.id','=', 'package_trainers.id_trainer')
                ->join('trainer__schedules','trainers.id','=', 'trainer__schedules.id_trainer' )
                ->where('package_trainers.id_package', $id_package)
                ->where( 'trainer__schedules.approval_status', TrainerSchedule::DA_DUYET)
                ->select( 'trainers.id', 'trainers.name','trainers.avatar',

                    DB::raw(" DATE_FORMAT( trainers.date_of_birth, '%d/%m/%Y' ) as date_of_birth"),

                    DB::raw(" DATE_FORMAT( trainer__schedules.date, '%d/%m/%Y' ) as date "),
                    'trainers.address',
                    'trainers.specialization',
                    'trainers.experience',
                    'trainer__schedules.title as schedule',
                    DB::raw("CONCAT( TIME_FORMAT( trainer__schedules.start_time, '%H:%i' ), ' - ', TIME_FORMAT( trainer__schedules.end_time,'%H:%i' ) ) as training_time "),

                    'trainer__schedules.current_members as total_students',
                    'trainer__schedules.note',
                    DB::raw("4.8 as rating"),
                    DB::raw("
                        CASE DAYOFWEEK(trainer__schedules.date)
                            WHEN 2 THEN 'T2'
                            WHEN 3 THEN 'T3'
                            WHEN 4 THEN 'T4'
                            WHEN 5 THEN 'T5'
                            WHEN 6 THEN 'T6'
                            WHEN 7 THEN 'T7'
                            WHEN 1 THEN 'CN'
                        END as active_day
                    ")
                )
                ->get();

        return response()->json([
            'status' => true,
            'message' => 'Lấy danh sách huấn luyện viên thành công.',
            'data' => $data,
        ]);
    }

    public function getPackage()
    {
        $data = Package::join('trainer__schedules', 'packages.id', '=', 'trainer__schedules.id_package')
            ->where('packages.status', Package::HOAT_DONG)
            ->where('trainer__schedules.approval_status', TrainerSchedule::DA_DUYET)
            ->select(
                'packages.id',
                'packages.name',
                'packages.price',
                'packages.duration_days',
                'packages.description',
                'packages.is_popular'
            )
            ->distinct()
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Lấy danh sách gói tập thành công.',
            'data' => $data,
        ], 200);
    }

    

    public function getScheduleDetail($id)
    {
        $data = TrainerSchedule::join('packages', 'trainer__schedules.id_package', '=', 'packages.id')
            ->join('trainers', 'trainer__schedules.id_trainer', '=', 'trainers.id')
            ->join('branches', 'trainer__schedules.id_branch', '=', 'branches.id')
            ->where('trainer__schedules.id', $id)
            ->select(
                'trainer__schedules.id',
                'trainer__schedules.title',
                // 'trainer__schedules.date',
                DB::raw(" DATE_FORMAT( trainer__schedules.date, '%d/%m/%Y' ) as date "),
                'trainer__schedules.start_time',
                'trainer__schedules.end_time',
                'trainer__schedules.room',

                'packages.name as package_name',
                'packages.price',

                'trainers.name as trainer_name',
                'trainers.avatar',
                'trainers.experience',

                'branches.name as branch_name'
            )
            ->first();
            $start = \Carbon\Carbon::parse($data->start_time);
            $end = \Carbon\Carbon::parse($data->end_time);

            $data->duration = $start->diffInMinutes($end);

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }
    public function getScheduleTitles()
    {
        $data = TrainerSchedule::select('id', 'title')->get();

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }
    public function getTrainerdetail($id)
    {
        $data = Trainer::join('trainer__schedules', 'trainers.id', '=', 'trainer__schedules.id_trainer')
                        ->where('trainer__schedules.approval_status', TrainerSchedule::DA_DUYET)
                        ->where('trainers.id', $id)
                        ->select(  'trainers.id',  'trainers.name',  'trainers.avatar',
                                    DB::raw("DATE_FORMAT(trainers.date_of_birth, '%d/%m/%Y') as date_of_birth"),
                                    DB::raw("DATE_FORMAT(trainer__schedules.date, '%d/%m/%Y') as date"),
                                    'trainers.address', 'trainers.specialization', 'trainers.experience', 'trainer__schedules.title as schedule',
                                    DB::raw("
                                        CONCAT(
                                            TIME_FORMAT(trainer__schedules.start_time, '%H:%i'),
                                            ' - ',
                                            TIME_FORMAT(trainer__schedules.end_time, '%H:%i')
                                        ) as training_time
                                    "),
                                    'trainer__schedules.current_members as total_students',
                                    'trainer__schedules.note',
                                    DB::raw("4.8 as rating"),
                                    DB::raw("
                                        CASE DAYOFWEEK(trainer__schedules.date)
                                            WHEN 2 THEN 'T2'
                                            WHEN 3 THEN 'T3'
                                            WHEN 4 THEN 'T4'
                                            WHEN 5 THEN 'T5'
                                            WHEN 6 THEN 'T6'
                                            WHEN 7 THEN 'T7'
                                            WHEN 1 THEN 'CN'
                                        END as active_day
                                    ")
                                )
        ->first();
        return response()->json([
            'status' => true,
            'message' => 'Lấy thông tin huấn luyện viên thành công.',
            'data' => $data,
        ], 200);
    }
    public function createOrder(CreateOrderRequest $request)
    {
        $member = Auth::guard('sanctum')->user();

        if (!$member) {
            return response()->json([
                'status' => false,
                'message' => 'Vui lòng đăng nhập.'
            ], 401);
        }
        $schedule = TrainerSchedule::join('packages', 'trainer__schedules.id_package', '=', 'packages.id')
        ->join('trainers', 'trainer__schedules.id_trainer', '=', 'trainers.id')
        ->join('branches', 'trainer__schedules.id_branch', '=', 'branches.id')
        ->where('trainer__schedules.id', $request->id_schedule)
        ->select(
            'trainer__schedules.*',
            'packages.name as package_name',
            'packages.price as package_price',
            'packages.duration_days as duration_days',
            'trainers.name as trainer_name',
            'trainers.avatar as trainer_avatar',
            'trainers.experience as trainer_experience',
            'branches.name as branch_name'
        )
        ->first();
        if (!$schedule) {
        return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy lịch tập.'
            ], 404);
        }
        $start = Carbon::parse($schedule->start_time);
        $end = Carbon::parse($schedule->end_time);
        $duration = $start->diffInMinutes($end);
        $subtotal = $schedule->package_price;
        $discount = $request->discount ?? 0;
        $promotionId = $request->id_promotion ?? null;
        $totalAmount = max(0, $subtotal - $discount);

        // test ma khuyen mai 
        // if ($request->filled('code')) {
        //     $promotion = Promotion::where('code',$request->code)
        //     ->where('status',1)
        //     ->first();

        //     if (!$promotion) {
        //         return response()->json([
        //             'status'=>false,
        //             'message'=>'Mã giảm giá không hợp lệ'
        //         ],400);
        //     }

        //     if (!$promotion) {
        //         return response()->json([
        //             'status' => false,
        //             'message' => 'Không tìm thấy khuyến mãi.'
        //         ], 404);
        //     }
        //     $promotionId = $promotion->id;

        //     if ($promotion->type == 0) {

        //         $discount = ($subtotal * $promotion->value) / 100;

        //         if ($promotion->max_discount) {
        //             $discount = min($discount, $promotion->max_discount);
        //         }

        //     } else {

        //         $discount = $promotion->value;
        //     }

        //     $promotion->increment('used_quantity');
        // }
        // $totalAmount = max(0, $subtotal - $discount);
        $orderCode = 'DH' . now()->format('YmdHis') . rand(100, 999);
        $order = DonHang::create([
            'id_member' => $member->id,
            'id_promotion' => $promotionId,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total_amount' => $totalAmount,
            'is_thanh_toan' => DonHang::CHUA_THANH_TOAN,
            'status' => DonHang::DANG_THANH_TOAN,
            'order_code' => $orderCode,
            'payment_method' => $request->payment_method,
        ]);


       $orderDetail = OrderDetail::create([
            'id_don_hang' => $order->id,

            'id_package' => $schedule->id_package,
            'id_trainer' => $schedule->id_trainer,
            'id_member' => $member->id,
            'id_schedule' => $schedule->id,
            'id_branch' => $schedule->id_branch,
            'package_name' => $schedule->package_name,
            'package_price' => $schedule->package_price,
            'trainer_name' => $schedule->trainer_name,
            'trainer_avatar' => $schedule->trainer_avatar,
            'trainer_experience' => $schedule->trainer_experience,
            'branch_name' => $schedule->branch_name,
            'schedule_title' => $schedule->title,
            'schedule_date' => $schedule->date,
            'start_time' => $schedule->start_time,
            'end_time' => $schedule->end_time,
            'duration' => $duration,
            'room' => $schedule->room,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total_amount' => $totalAmount,
            'status' => OrderDetail::CHO_DUYET,
        ]);

        $memberPackage = MemberPackage::where('id_member', $member->id)
            ->where('status', 1)
            ->first();
        MemberPackage::create([
            'price'           => $schedule->package_price,
            'start_date'      => now()->toDateString(),
            'end_date'        => now()->addDays($schedule->duration_days)->toDateString(),

            // Thay bằng giá trị thực của gói nếu có trong bảng packages
            'total_sessions'  => 16,
            'used_sessions'   => 0,
            'pt_sessions'     => 4,

            'status'          => 1, // Đang hoạt động

            'id_trainer'      => $schedule->id_trainer,
            'id_member'       => $member->id,
            'id_package'      => $schedule->id_package,
        ]);




        // $exists = ScheduleMember::where('id_member', $member->id)
        //     ->where('id_schedule', $schedule->id)
        //     ->exists();

        // if ($exists) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Bạn đã đăng ký lịch tập này rồi.',
        //     ], 400);
        // }
        ScheduleMember::create([
            'id_member'   => $member->id,
            'id_schedule' => $schedule->id,
            'id_trainer_schedule' => $schedule->id,
            'id_package'  => $schedule->id_package,
            'id_order_detail' => $orderDetail->id,
            'status'      => 0, // Sắp tới
        ]);



        return response()->json([
            'status' => true,
            'message' => 'Tạo đơn hàng thành công.',
            'data' => $order,
        ]);
    }
    public function checkPromotion(Request $request)
    {
        if (!$request->code) {
            return response()->json([
                'status' => false,
                'message' => 'Vui lòng nhập mã giảm giá.'
            ], 400);
        }
        if (!$request->subtotal) {
            return response()->json([
                'status' => false,
                'message' => 'Không có giá tiền.'
            ], 400);
        }
        $promotion = Promotion::where('code', $request->code)->first();

        if (!$promotion) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy khuyến mãi.'
            ], 404);
        }
        if ($promotion->status != 1) {
            return response()->json([
                'status' => false,
                'message' => 'Mã giảm giá đã bị khóa.'
            ], 400);
        }

        // Kiểm tra thời gian
        if ($promotion->start_at && now()->lt($promotion->start_at)) {
            return response()->json([
                'status' => false,
                'message' => 'Mã giảm giá chưa bắt đầu.'
            ], 400);
        }


        if ($promotion->end_at && now()->gt($promotion->end_at)) {
            return response()->json([
                'status' => false,
                'message' => 'Mã giảm giá đã hết hạn.'
            ], 400);
        }


        // Kiểm tra số lượng
        if ($promotion->quantity > 0 
            && $promotion->used_quantity >= $promotion->quantity) {

            return response()->json([
                'status' => false,
                'message' => 'Mã giảm giá đã hết lượt sử dụng.'
            ], 400);
        }

        $discount = 0;
        if ($promotion->type == 0) {
            // giảm %
            $discount = ($request->subtotal * $promotion->value) / 100;
            if ($promotion->max_discount) {
                $discount = min(
                    $discount,
                    $promotion->max_discount
                );
            }
        } else {
            // giảm tiền
            $discount = $promotion->value;

        }
        return response()->json([
            'status' => true,
            'message' => 'Áp dụng mã giảm giá thành công.',
            'data' => [
                'promotion_id' => $promotion->id,
                'code' => $promotion->code,
                'discount' => $discount,
                'total_amount' => max(
                    0,
                    $request->subtotal - $discount
                )
            ]
        ]);
    }
    public function checkPayment($orderCode)
    {
        $order = DonHang::where('order_code', $orderCode)->first();

        if(!$order){
            return response()->json([
                'status'=>false,
                'message'=>'Không tìm thấy đơn hàng'
            ],404);
        }


        return response()->json([
            'status'=>true,
            'is_thanh_toan'=>$order->is_thanh_toan
        ]);
    }











    



}
