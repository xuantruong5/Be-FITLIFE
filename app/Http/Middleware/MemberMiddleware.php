<?php

namespace App\Http\Middleware;
use App\Models\Member;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MemberMiddleware
{
   
    public function handle(Request $request, Closure $next): Response
    {
        $check = Auth::guard('sanctum')->check();
        if (!$check) {
            return response()->json(['message' => 'Phiên đăng nhập hết hạn'], 401);
        }
        $user = Auth::guard('sanctum')->user();
        // Kiểm tra vai trò của user
        if($user && $user instanceof \App\Models\Member){
            if($user->status == Member::BLOCKED){
                return response()->json(['message' => 'Tài khoản của bạn đã bị khóa'], 403);
            }
            return $next($request);
        }
        return response()->json(['message' => 'Bạn không có quyền truy cập'], 403);

    }
}
