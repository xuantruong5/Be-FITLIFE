<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
}
