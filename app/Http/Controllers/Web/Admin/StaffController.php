<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class StaffController extends Controller
{
    public function users() {
        if (Auth::user()->user_role_id == 2) { //if info admin
            $staffCount = User::where('user_role_id', 5)->count();
            $title = 'Promote User to Information Staff';
        }

        $users = User::with('user_role')->orWhere('user_role_id', 8)->orWhere('user_role_id', 9)->orderBy('created_at', 'DESC')->get();
        return view('admin.staffs.promote-user', compact('users', 'title', 'staffCount'));

    }

    public function promoteUser(User $user) {
        if (Auth::user()->user_role_id == 2) { //if info admin
            $user->fill(['user_role_id' => 5, 'status' => 'Enable', 'is_verified' => 1])->save();
            $userRole = UserRole::find(5);
        }

        Mail::send('email.promoteUser', ['userRole' => $userRole], function($message) use($user){
            $message->to($user->email);
            $message->subject('User Promotion');
        });

        $user->load('user_role');
        return response()->json(['success' => true, 'message' => $user->getFullNameAttribute().' is promoted to '.$user->user_role->role]);
    }

    public function adminStaff() {
        if (Auth::user()->user_role_id == 2) { //if info admin
            $users = User::with('user_role')->where('user_role_id', 5)->orderBy('created_at', 'DESC')->get();
            $title = 'Information Admin Staff';
        }

        return view('admin.staffs.staff', compact('users', 'title'));
    }

    public function demoteStaff(User $user) {
        $user->fill(['user_role_id' => 9])->save();
        $userRole = UserRole::find(9);

        Mail::send('email.demoteUser', ['userRole' => $userRole], function($message) use($user){
            $message->to($user->email);
            $message->subject('User Demotion');
        });

        return response()->json(['success' => true, 'message' => $user->getFullNameAttribute().' is demoted to basic user (Resident)']);

    }
}