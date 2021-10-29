<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class StaffController extends Controller
{
    public function users() {
        $users = User::with('user_role')->orWhere('user_role_id', 8)->orWhere('user_role_id', 9)->orderBy('created_at', 'DESC')->get();
        $staffCount = User::where('user_role_id', 5)->count();
        $title = 'Promote User to Information Staff';
        return view('admin.users.promote-user', compact('users', 'title', 'staffCount'));
    }

    public function promoteUser(User $user) {
        $user->fill(['user_role_id' => 5, 'status' => 'Enable', 'is_verified' => 1])->save();
        $user->load('user_role');
        return response()->json(['success' => true, 'message' => $user->getFullNameAttribute().' is promoted to '.$user->user_role->role]);
    }

    public function adminStaff() {
        $users = User::with('user_role')->where('user_role_id', 5)->orderBy('created_at', 'DESC')->get();
        $title = 'Information Admin Staff';
        return view('admin.users.staff', compact('users', 'title'));
    }

    public function demoteStaff(User $user) {
        $user->fill(['user_role_id' => 9])->save();
        return response()->json(['success' => true, 'message' => $user->getFullNameAttribute().' is demoted to basic user (Resident)']);
    }
}
