<?php

namespace App\Http\Middleware;
use App\Models\Trainer;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TrainerMiddleware
{
    
    public function handle(Request $request, Closure $next): Response
    {
        $check = Auth::guard('sanctum')->check();
        if (!$check) {
            return response()->json(['message' => 'Phiên đăng nhập hết hạn'], 401);
        }
        $traniner = Auth::guard('sanctum')->user();
        // Kiểm tra vai trò của traniner
        if($traniner && $traniner instanceof \App\Models\Trainer){
            if($traniner->status == Trainer::BLOCKED){
                return response()->json(['message' => 'Tài khoản của bạn đã bị khóa'], 403);
            }
            return $next($request);
        }
        return response()->json(['message' => 'Bạn không có quyền truy cập'], 403);

    }
}
