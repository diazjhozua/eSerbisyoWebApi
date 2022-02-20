<?php

namespace App\Http\Middleware;
use Tymon\JWTAuth\Facades\JWTAuth;

use Closure;
use Illuminate\Http\Request;
use Log;

class JWTMiddleWare
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $userMainRole)
    {
        $message = '';
        Log::debug(JWTAuth::getToken());
        try {
            // checks token validations

            if ($userMainRole == 'isVerified') {
                if (auth('api')->user()->is_verified == 0) {
                    return response()->json([
                            'message' => "Your account must be verified to access this function."
                        ], 403);
                }
            }

            if ($userMainRole == 'isBiker') {
                if (auth('api')->user()->user_role_id != 8) {
                    return response()->json([
                            'message' => "Your account must have biker priviledges to access this system"
                        ], 403);
                }
            }

            JWTAuth::parseToken()->authenticate();
            return $next($request);
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            // do whatever you want to do if a token is expired
            $message = 'Token expired! Login first';
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            // do whatever you want to do if a token is invalid
            $message = 'Invalid token! Login first';
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            // do whatever you want to do if a token is not present
            $message = 'Provide token! Login first';
        }
        return response()->json([
                'message' => $message
            ], 403);
    }

    // public function handle(Request $request, Closure $next)
    // {
    //     $message = '';
    //     Log::debug(JWTAuth::getToken());
    //     try {
    //         // checks token validations
    //         JWTAuth::parseToken()->authenticate();
    //         return $next($request);
    //     } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
    //         // do whatever you want to do if a token is expired
    //         $message = 'Token expired! Login first';
    //     } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
    //         // do whatever you want to do if a token is invalid
    //         $message = 'Invalid token! Login first';
    //     } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
    //         // do whatever you want to do if a token is not present
    //         $message = 'Provide token! Login first';
    //     }
    //     return response()->json([
    //             'message' => $message
    //         ], 403);
    // }
}
