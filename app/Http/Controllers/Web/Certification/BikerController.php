<?php

namespace App\Http\Controllers\Web\Certification;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserVerificationRequest;
use App\Http\Resources\BikerRequestResource;
use App\Jobs\ChangeRoleJob;
use App\Jobs\VerifyUserJob;
use App\Models\BikerRequest;
use App\Models\Order;
use App\Models\User;
use Helper;
use Response;

class BikerController extends Controller
{
    // list of bikers
    public function index() {
        $bikers = User::with('user_role')->with('latest_biker_request')->where('user_role_id', 8)->orderBy('created_at', 'DESC')->get();

        return view('admin.certification.bikers.index', compact('bikers'));
    }

    // biker profile (past transaction)
    public function bikerProfile(User $user) {
        $user = $user->load('latest_biker_request');
        $orders = Order::with('certificateForms', 'contact')->where('delivered_by', '=', $user->id)->get();

        return view('admin.certification.bikers.profile', compact('orders', 'user'));
    }

    // list of application request submitted by the user
    public function applicationRequests() {
        $applications = BikerRequest::orderBy('created_at', 'DESC')->get();
        return view('admin.certification.bikers.applicationList', compact('applications'));
    }

    // view single application request
    public function getSingleApplication(BikerRequest $bikerRequest) {
        return (new BikerRequestResource($bikerRequest->load('user.purok')))->additional(Helper::instance()->itemFound('biker_verification_request'));
    }

    // verify user
    public function verifyApplication(UserVerificationRequest $request, BikerRequest $bikerRequest) {

        if ($bikerRequest->status != 'Pending') {
            return response()->json(['success' => false, 'message' => 'Once the request is already denied or approved'], 400);
        }

        $bikerRequest->fill($request->validated())->save();

        $user = User::findOrFail($bikerRequest->user_id);

        if ($request->status == 'Approved') {
            $user->fill([
                'bike_type' => $bikerRequest->bike_type,
                'bike_color' => $bikerRequest->bike_color,
                'bike_size' => $bikerRequest->bike_size,
                ])->save();
        }

        $subject = $request->status == 'Approved' ? 'Verified Bikers Account' : 'Failed Biker Application Verification Account';

        dispatch(new VerifyUserJob($user, $subject, $request->all()));

        return (new BikerRequestResource($bikerRequest))->additional(Helper::instance()->updateSuccess($user->getFullNameAttribute().' verification request - '. strtolower($request->status)));
    }

    // demote biker to user
    public function demoteBiker(User $user) {
        $user->fill(['user_role_id' => 9, 'status' => 'Enable', 'is_verified' => 1])->save();

        $user->load('user_role');
        $subject = 'User Demotion';
        dispatch(new ChangeRoleJob($user, $subject, $user->user_role));

        return response()->json(['success' => true, 'message' => $user->getFullNameAttribute().' is demoted to basic user (Resident)']);
    }

}
