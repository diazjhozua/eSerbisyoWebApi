<?php

namespace App\Http\Controllers\Web\Certification;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserVerificationRequest;
use App\Http\Resources\BikerRequestResource;
use App\Jobs\ChangeRoleJob;
use App\Jobs\SendSingleNotificationJob;
use App\Jobs\VerifyUserJob;
use App\Models\BikerRequest;
use App\Models\Order;
use App\Models\User;
use DB;
use Helper;
use Log;
use Response;

class BikerController extends Controller
{
    // list of bikers
    public function index() {
        $bikers = User::with('user_role')->with('latest_biker_request')->where('user_role_id', 8)->orderBy('id', 'DESC')->get();
        return view('admin.certification.bikers.index', compact('bikers'));
    }

    // biker profile (past transaction)
    public function bikerProfile(User $user) {
        $user = $user->load('latest_biker_request', 'deliverySuccess');
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
                'user_role_id' => 8,
                'phone_no' => $bikerRequest->phone_no,
                'bike_type' => $bikerRequest->bike_type,
                'bike_color' => $bikerRequest->bike_color,
                'bike_size' => $bikerRequest->bike_size,
                ])->save();
        }

        $subject = $request->status == 'Approved' ? 'Verified Bikers Account' : 'Failed Biker Application Verification Account';

        dispatch(
            new SendSingleNotificationJob(
                $user->device_id, $user->id, $request->status == 'Approved' ? 'Verified Biker Application' : 'Failed Biker Application Verification Account',
                "Your submitted biker application has been responded by the administrator.", $bikerRequest->id,  "App\Models\BikerRequest"
        ));

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

    public function report ($biker_id, $date_start,  $date_end, $sort_column, $sort_option, $order_status,) {
        $title = 'Report - No data';
        $description = 'No data';

        $user = User::with('latest_biker_request', 'deliverySuccess')->findOrFail($biker_id);

        try {

            $orders = Order::with('certificateForms', 'contact')->where('delivered_by', '=', $biker_id)
                ->whereBetween('created_at', [$date_start, $date_end])
                ->where(function($query) use ($order_status) {
                        if($order_status == 'all') {
                            return null;
                        } else {
                            return $query->where('order_status', '=', $order_status);
                        }
                    })
                ->orderBy($sort_column, $sort_option)
                ->get();
        } catch(\Illuminate\Database\QueryException $ex){
            return view('errors.404Report', compact('title', 'description'));
        }

        if ($orders->isEmpty()) {
            return view('errors.404Report', compact('title', 'description'));
        }

        $reportsData = null;

        $reportsData =  DB::table('orders')
            ->selectRaw('count(*) as orders_count')
            ->selectRaw("count(case when order_status = 'Received' then 1 end) as received_count")
            ->selectRaw("count(case when order_status = 'DNR' then 1 end) as dnr_count")
            ->where('delivered_by', '=', $biker_id)
            ->where('created_at', '>=', $date_start)
            ->where('created_at', '<=', $date_end)
            ->where(function($query) use ($order_status) {
                if($order_status == 'all') {
                    return null;
                } else {
                    return $query->where('order_status', '=', $order_status);
                }
            })
            ->first();

        $title = $user->getFullNameAttribute().' #'.$user->id.' Biker Transaction Reports';
        $modelName = 'Biker Delivery History';

        return view('admin.certification.pdf.bikerTransactions', compact('title', 'modelName', 'user', 'orders', 'reportsData',
            'date_start', 'date_end', 'sort_column', 'sort_option','order_status'
        ));
    }

}
