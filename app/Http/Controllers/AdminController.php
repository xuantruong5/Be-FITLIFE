<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Admin\LoginAdminRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;

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




}
