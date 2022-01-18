<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserVerificationRequest;
use App\Http\Resources\UserVerificationResource;
use App\Jobs\VerifyUserJob;
use App\Models\User;
use App\Models\UserVerification;
use Helper;
use Illuminate\Http\Request;

class UserVerificationController extends Controller
{
    public function index()
    {
        $userVerifications = UserVerification::with('user')->orderBy('created_at','DESC')->get();

        return view('admin.information.verificationRequests.index', compact('userVerifications'));
    }

    public function edit(UserVerification $user_verification)
    {
        return (new UserVerificationResource($user_verification->load('user.purok')))->additional(Helper::instance()->itemFound('user_verification_request'));
    }

    public function update(UserVerificationRequest $request, UserVerification $user_verification)
    {
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

        $subject = $request->status == 'Approved' ? 'Verified Account' : 'Failed Verification Account';

        dispatch(new VerifyUserJob($user, $subject, $request->all()));

        return (new UserVerificationResource($user_verification->load('user.purok')))->additional(Helper::instance()->updateSuccess($user->getFullNameAttribute().' verification request - '. strtolower($request->status)));
    }
}
