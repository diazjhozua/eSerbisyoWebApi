<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserPositionRequest;
use App\Jobs\ChangeRoleJob;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Log;

class StaffController extends Controller
{
    public function users() {
        if (Auth::user()->user_role_id == 1) { //if info admin
            $staffCount = User::where('user_role_id', 5)->count();
            $title = 'Promote User to Admin or Staff';
        }

        if (Auth::user()->user_role_id == 2) { //if info admin
            $staffCount = User::where('user_role_id', 5)->count();
            $title = 'Promote User to Information Staff';
        }

        if (Auth::user()->user_role_id == 3) { //if certificate admin
            $staffCount = User::where('user_role_id', 6)->count();
            $title = 'Promote User to Certificate Staff';
        }

        if (Auth::user()->user_role_id == 4) { //if info admin
            $staffCount = User::where('user_role_id', 7)->count();
            $title = 'Promote User to Taskforce Staff';
        }

        $users = User::with('user_role')->orWhere('user_role_id', 8)->orWhere('user_role_id', 9)->orderBy('id', 'DESC')->get();
        return view('admin.staffs.promote-user', compact('users', 'title', 'staffCount'));

    }

    public function promoteUser(User $user) {
        if (Auth::user()->user_role_id == 2) { //if info admin
            $user->fill(['user_role_id' => 5, 'status' => 'Enable', 'is_verified' => 1])->save();
        }

        if (Auth::user()->user_role_id == 3) { //if certificate admin
            $user->fill(['user_role_id' => 6, 'status' => 'Enable', 'is_verified' => 1])->save();
        }

        if (Auth::user()->user_role_id == 4) { //if taskforce admin
            $user->fill(['user_role_id' => 7, 'status' => 'Enable', 'is_verified' => 1])->save();
        }

        $user->load('user_role');
        $subject = 'User Promotion';
        dispatch(new ChangeRoleJob($user, $subject, $user->user_role));

        return response()->json(['success' => true, 'message' => $user->getFullNameAttribute().' is promoted to '.$user->user_role->role]);
    }

    public function promoteToAnyPosition(UserPositionRequest $request,  User $user) {

        $user->fill(['user_role_id' => $request->user_role_id, 'status' => 'Enable', 'is_verified' => 1])->save();

        $user->load('user_role');
        $subject = 'User Promotion';
        dispatch(new ChangeRoleJob($user, $subject, $user->user_role));

        return response()->json(['success' => true, 'message' => $user->getFullNameAttribute().' is promoted to '.$user->user_role->role]);
    }

    public function adminStaff() {
        if (Auth::user()->user_role_id == 1) { //if info admin
            $users = User::with('user_role')->where('user_role_id', '<=', '7')->orderBy('created_at', 'DESC')->get();
            $title = 'Barangay Cupang Admin or Staff';
        }

        if (Auth::user()->user_role_id == 2) { //if info admin
            $users = User::with('user_role')->where('user_role_id', 5)->orderBy('created_at', 'DESC')->get();
            $title = 'Information Admin Staff';
        }

        if (Auth::user()->user_role_id == 3) { //if certificate admin
            $users = User::with('user_role')->where('user_role_id', 6)->orderBy('created_at', 'DESC')->get();
            $title = 'Certificate Admin Staff';
        }

        if (Auth::user()->user_role_id == 4) { //if info admin
            $users = User::with('user_role')->where('user_role_id', 7)->orderBy('created_at', 'DESC')->get();
            $title = 'Taskforce Admin Staff';
        }

        return view('admin.staffs.staff', compact('users', 'title'));
    }

    public function demoteStaff(User $user) {
        $user->fill(['user_role_id' => 9, 'status' => 'Enable', 'is_verified' => 1])->save();

        $user->load('user_role');
        $subject = 'User Demotion';
        dispatch(new ChangeRoleJob($user, $subject, $user->user_role));

        return response()->json(['success' => true, 'message' => $user->getFullNameAttribute().' is demoted to basic user (Resident)']);

    }
}
