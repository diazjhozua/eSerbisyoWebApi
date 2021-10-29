<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeUserStatusRequest;
use App\Http\Requests\UserProfileRequest;
use App\Http\Requests\UserVerificationRequest;
use App\Http\Resources\UserProfileResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserVerificationResource;
use App\Models\User;
use App\Models\UserVerification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Storage;

class UserController extends Controller
{

    //view page with registered user (All User)
    public function index() {
        $firstDayYear = date('Y-m-d', strtotime('first day of january this year'));
        $lastDateYear = date('Y-m-d', strtotime('first day of december this year'));
        $firstDayMonth = date('Y-m-d',strtotime('first day of this month'));
        $lastDayMonth = date('Y-m-d',strtotime('last day of this month'));

        $usersData =  DB::table('users')
        ->selectRaw("count(case when created_at >='". $firstDayYear ."' AND created_at <='".$lastDateYear."' then 1 end) as this_year_count")
        ->selectRaw("count(case when created_at >='". $firstDayMonth ."' AND created_at <='".$lastDayMonth."' then 1 end) as this_month_count")
        ->selectRaw("count(case when DATE(created_at) = CURDATE() then 1 end) as this_day_count")
        ->selectRaw("count(case when status = 'Disable' then 1 end) as blocked_user_count")
        ->first();

        $verificationCount = UserVerification::where('status', 'Pending')->count();

        $users = User::with('user_role', 'latest_user_verification')->orderBy('created_at','DESC')->get();

        return view('admin.users.index', compact('usersData', 'verificationCount','users'));
    }

    // changeUserStatus
    public function changeUserStatus(ChangeUserStatusRequest $request, User $user) {
        $user->fill($request->validated())->save();
        return (new UserResource($user->load(['user_role', 'latest_user_verification' => function ($query) {
            $query->where('status', 'Pending');
        }])))->additional(Helper::instance()->updateSuccess('user status -  '. strtolower($request->status)));
    }

    // view verification request
    public function viewUserVerification(UserVerification $user_verification) {
        return (new UserVerificationResource($user_verification->load('user.purok')))->additional(Helper::instance()->itemFound('user_verification_request'));
    }

    // verify user
    public function verifyUser(UserVerificationRequest $request, UserVerification $user_verification) {
        if ($user_verification->status != 'Pending') {
            return response()->json(['success' => false, 'message' => 'Once the request is already denied or approved']);
        }

        $user_verification->fill($request->validated())->save();
        $user = User::with(['user_role', 'latest_user_verification' => function ($query) {
            $query->where('status', 'Pending');
        }])->findOrFail($user_verification->user_id);

        if ($request->status == 'Approved') {
            $user->fill(['is_verified' => 1])->save();
        }

        return (new UserResource($user))->additional(Helper::instance()->updateSuccess('user verification request - '. strtolower($request->status)));
    }

    // view user admin profile
    public function profile() {
        $user = User::with('user_role')->findOrFail(Auth::id());
        return view('admin.users.user-profile', compact('user'));
    }

    public function editProfile() {
        if(request()->ajax()) {
            $user = User::with('user_role')->findOrFail(Auth::id());
            return (new UserProfileResource($user))->additional(Helper::instance()->itemFound('Fetch latest authenticated credentials'));
        }
    }

    public function updateProfile(UserProfileRequest $request) {
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
}
