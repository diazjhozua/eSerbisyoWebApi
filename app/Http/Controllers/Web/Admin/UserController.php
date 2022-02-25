<?php

namespace App\Http\Controllers\Web\Admin;

use App;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeUserStatusRequest;
use App\Http\Requests\Report\UserReportRequest;
use App\Http\Requests\UserVerificationRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserVerificationResource;
use App\Jobs\StatusUserJob;
use App\Jobs\VerifyUserJob;
use App\Models\User;
use App\Models\UserVerification;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    //view page with registered user (All User)
    public function index() {
        $firstDayYear = date('Y-m-d', strtotime('first day of january this year'));
        $lastDateYear = date('Y-m-d', strtotime('first day of december this year'));
        $firstDayMonth = date('Y-m-d',strtotime('first day of this month'));
        $lastDayMonth = date('Y-m-d',strtotime('last day of this month'));

        if (App::environment('production')) {
            $usersData =  DB::table('users')
                ->selectRaw("count(case when created_at >='". $firstDayYear ."' AND created_at <='".$lastDateYear."' then 1 end) as this_year_count")
                ->selectRaw("count(case when created_at >='". $firstDayMonth ."' AND created_at <='".$lastDayMonth."' then 1 end) as this_month_count")
                ->selectRaw("count(case when DATE(created_at) = CURRENT_DATE then 1 end) as this_day_count")
                ->selectRaw("count(case when status = 'Disable' then 1 end) as blocked_user_count")
                ->first();
        } else {
            $usersData =  DB::table('users')
                ->selectRaw("count(case when created_at >='". $firstDayYear ."' AND created_at <='".$lastDateYear."' then 1 end) as this_year_count")
                ->selectRaw("count(case when created_at >='". $firstDayMonth ."' AND created_at <='".$lastDayMonth."' then 1 end) as this_month_count")
                ->selectRaw("count(case when DATE(created_at) = CURDATE() then 1 end) as this_day_count")
                ->selectRaw("count(case when status = 'Disable' then 1 end) as blocked_user_count")
                ->first();

        }


        $verificationCount = UserVerification::where('status', 'Pending')->count();

        $users = User::with('user_role', 'latest_user_verification')->orderBy('created_at','DESC')->get();

        return view('admin.information.users.index', compact('usersData', 'verificationCount','users'));
    }

    // changeUserStatus
    public function changeUserStatus(ChangeUserStatusRequest $request, User $user) {
        $user->fill($request->validated())->save();
        $subject = $user->request == 'Enable' ? 'Enabled Account' : 'Disabled Account';

        dispatch(new StatusUserJob($user, $subject, $request->all()));

        return (new UserResource($user->load(['user_role', 'latest_user_verification' => function ($query) {
            $query->where('status', 'Pending');
        }])))->additional(Helper::instance()->updateSuccess($user->getFullNameAttribute().' status -  '. strtolower($request->status)));
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

        $subject = $request->status == 'Approved' ? 'Verified Account' : 'Failed Verification Account';

        dispatch(new VerifyUserJob($user, $subject, $request->all()));

        return (new UserResource($user))->additional(Helper::instance()->updateSuccess($user->getFullNameAttribute().' verification request - '. strtolower($request->status)));
    }

    public function report($date_start,  $date_end, $filter, $sort_column, $sort_option) {

        $title = 'Report - No data';
        $description = 'No data';
        try {

         $users = User::with('user_role')
            ->whereBetween('created_at', [$date_start, $date_end])
            ->orderBy($sort_column, $sort_option)
            ->where(function($query) use ($date_start,$filter,$date_end) {
                if($filter == 'all') {
                    return null;
                } elseif ($filter == 'enable') {
                    return $query->where('status', '=', 'Enable');
                } elseif ($filter == 'verified') {
                    return $query->where('is_verified', '=', 1);
                } elseif ($filter == 'unverified') {
                    return $query->where('is_verified', '=', 0);
                } else {
                    return $query->where('status', '=', 'Disable');
                }
            })->get();
        } catch(\Illuminate\Database\QueryException $ex){
            return view('errors.404Report', compact('title', 'description'));
        }
        // if ($users->isEmpty()) {
        //     return response()->json(['No data'], 404);
        // }

        $usersData = null;
        $firstDayYear = date('Y-m-d', strtotime('first day of january this year'));
        $lastDateYear = date('Y-m-d', strtotime('first day of december this year'));
        $firstDayMonth = date('Y-m-d',strtotime('first day of this month'));
        $lastDayMonth = date('Y-m-d',strtotime('last day of this month'));

        if($filter == 'all') {
            if (App::environment('production')) {
                $usersData =  DB::table('users')
                    ->selectRaw('count(*) as users_count')
                    ->selectRaw("count(case when status = 'Enable' then 1 end) as enable_user_count")
                    ->selectRaw("count(case when status = 'Disable' then 1 end) as disable_user_count")
                    ->selectRaw("count(case when is_verified = false then 1 end) as unverified_user_count")
                    ->selectRaw("count(case when is_verified = true then 1 end) as verified_user_count")
                    ->where('created_at', '>=', $date_start)
                    ->where('created_at', '<=', $date_end)
                    ->first();

            } else {
                $usersData =  DB::table('users')
                    ->selectRaw('count(*) as users_count')
                    ->selectRaw("count(case when status = 'Enable' then 1 end) as enable_user_count")
                    ->selectRaw("count(case when status = 'Disable' then 1 end) as disable_user_count")
                    ->selectRaw("count(case when is_verified = 0 then 1 end) as unverified_user_count")
                    ->selectRaw("count(case when is_verified = 1 then 1 end) as verified_user_count")
                    ->where('created_at', '>=', $date_start)
                    ->where('created_at', '<=', $date_end)
                    ->first();
            }
        }
        $title = 'User Reports';
        $modelName = 'User';


        return view('admin.information.pdf.usersreport', compact('title', 'modelName', 'users', 'usersData',
            'date_start', 'date_end','filter', 'sort_column', 'sort_option'
        ));
    }

}
