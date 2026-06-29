<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class TrainerController extends Controller
{
    public function test()
    {
         return response()->json([
            'message' => 'Test API thành công'
        ]);
    }

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
}
