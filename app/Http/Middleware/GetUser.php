<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class GetUser
{
    public function handle(Request $request, Closure $next)
    {
        if(!$user = JWTAuth::parseToken()->authenticate()) {
            return response()->json([
                'success' => false,
                'message' => 'User not authorized'
            ], 401);
        }

        $request->merge(['user_id' => $user->id]);

        return $next($request);
    }
}
