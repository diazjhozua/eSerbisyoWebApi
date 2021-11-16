<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeEmailRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UserProfileRequest;
use App\Http\Resources\UserProfileResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Storage;

class ProfileController extends Controller
{
    // view user admin profile
    public function index() {
        $user = User::with('user_role')->findOrFail(Auth::id());
        return view('admin.profile.index', compact('user'));
    }

    // fetch auth user info
    public function edit() {
        if(request()->ajax()) {
            $user = User::with('user_role')->findOrFail(Auth::id());
            return (new UserProfileResource($user))->additional(Helper::instance()->itemFound('Fetch latest authenticated credentials'));
        }
    }

    //update user profile
    public function update(UserProfileRequest $request) {
        if(request()->ajax()) {
            $user = User::with('user_role')->findOrFail(Auth::id());
            if($request->hasFile('picture')) {
                Storage::delete('public/users/'. $user->picture_name);
                $fileName = time().'_'.$request->picture->getClientOriginalName();
                $filePath = $request->file('picture')->storeAs('users', $fileName, 'public');
                $user->fill(array_merge($request->getData(), ['picture_name' => $fileName,'file_path' => $filePath]))->save();
            } else {  $user->fill($request->getData())->save(); }

            return (new UserProfileResource($user))->additional(Helper::instance()->updateSuccess('user profile'));
        }
    }

    // change password
    public function changePassword(ChangePasswordRequest $request) {
        if(request()->ajax()) {
            User::find(Auth::id())->update(['password'=> Hash::make($request->new_password)]);
            return response()->json(Helper::instance()->updateSuccess('user password'));
        }
    }

    // change email
    public function changeEmail(ChangeEmailRequest $request) {
        if(request()->ajax()) {
            User::find(Auth::id())->update(['email'=> $request->new_email]);
            return response()->json(array_merge(['email' => $request->new_email ], Helper::instance()->updateSuccess('user email')));
        }
    }
}
