<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class isAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $userMainRole)
    {
        // id 1 = "Super Admin"
        // id 2 = "Information Admin"
        // id 3 = "Certification Admin"
        // id 4 = "TaskForce Admin"
        // id 5 = "Information Staff"
        // id 6 = "Certification Staff"
        // id 7 = "TaskForce Admin Staff"
        // id 8 = "Biker"
        // id 9 = "Resident"

        if ($userMainRole == 'infoStaff') {
            if (Auth::user()->user_role_id == 1 || Auth::user()->user_role_id == 2 || Auth::user()->user_role_id == 5) {
                return $next($request);
            }
        } elseif ($userMainRole == 'infoAdmin') {
            if (Auth::user()->user_role_id == 1 || Auth::user()->user_role_id == 2) {
                return $next($request);
            }
        } elseif ($userMainRole == 'taskForceAdmin') {
            if (Auth::user()->user_role_id == 1 || Auth::user()->user_role_id == 4) {
                return $next($request);
            }
        } elseif ($userMainRole =='admin') {
            if (Auth::user()->user_role_id < 5) {
                return $next($request);
            }
        } elseif ($userMainRole =='notBasicUser') {
            if (Auth::user()->user_role_id < 8) {
                return $next($request);
            }
        }

        return back();
    }
}
