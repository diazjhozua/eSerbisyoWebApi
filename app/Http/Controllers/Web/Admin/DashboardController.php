<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Project;
use App\Models\User;
use App\Models\UserVerification;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function informationAdmin() {

        $users = User::select('id', 'created_at')
            ->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('m'); // grouping by years
        });
        $userAverageRegistration = [];
        $userChart = [];

        foreach ($users as $key => $value) {
            $yearList = [];
            $userRegisterCount = 0;

            foreach ($value as $userRegistrationData) {
                $userRegisterCount ++;
                $year = Carbon::parse($userRegistrationData->created_at)->format('Y');
                if (!in_array($year, $yearList)) {
                    array_push($yearList, $year);
                }
            }
            $userAverageRegistration[(int)$key] = round($userRegisterCount / count($yearList), 2);
        }

        for($i = 1; $i <= 12; $i++){
            if(!empty($userAverageRegistration[$i])){
                $userChart[$i] = $userAverageRegistration[$i];
            }else{
                $userChart[$i] = 0;
            }
        }

        $projectRawData = [];
        $projectChart = [];

        $projects = Project::select('id', 'created_at')
            ->whereYear('created_at', '=', date("Y") )
            ->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('m'); // grouping by years
        });

        foreach ($projects as $key => $value) {
            $projectRawData[(int)$key] = $value->count();
        }

        for($i = 1; $i <= 12; $i++){
            if(!empty($projectRawData[$i])){
                $projectChart[$i] = $projectRawData[$i];
            }else{
                $projectChart[$i] = 0;
            }
        }

        $usersData = DB::table('users')
            ->selectRaw("count(case when created_at >='". date('Y-m-d',strtotime('first day of this month')) ."' AND created_at <='".date('Y-m-d',strtotime('last day of this month'))."' then 1 end) as this_month_count")
            ->selectRaw("count(case when user_role_id = 5 then 1 end) as information_staff_count")
            ->selectRaw("count(case when status = 'Disable' then 1 end) as blocked_user_count")
            ->first();

        $thisMonthProjectCount = $projectChart[date('m')];
        $verificationCount = UserVerification::where('status', 'Pending')->count();
        $announcementCount = Announcement::where('created_at', '>=', date('Y-m-d',strtotime('first day of this month')))
            ->where('created_at', '<=', date('Y-m-d',strtotime('last day of this month')))->count();

        $feedbacksData = DB::table('feedbacks')
            ->selectRaw("count(*) as this_month_total_feedbacks ")
            ->selectRaw("count(case when polarity = 'Positive' then 1 end) as this_month_positive_count")
            ->selectRaw("count(case when polarity = 'Neutral' then 1 end) as this_month_neutral_count")
            ->selectRaw("count(case when polarity = 'Negative' then 1 end) as this_month_negative_count")
            ->whereRaw("created_at >= '". date('Y-m-d',strtotime('first day of this month')) ."' AND created_at <='".date('Y-m-d',strtotime('last day of this month'))."'")
            ->first();

        $feedbacksData->this_month_positive_count = $feedbacksData->this_month_total_feedbacks == 0  ? 0 : round(($feedbacksData->this_month_positive_count / $feedbacksData->this_month_total_feedbacks) * 100, 2);
        $feedbacksData->this_month_neutral_count = $feedbacksData->this_month_total_feedbacks == 0  ? 0 :  round(($feedbacksData->this_month_neutral_count / $feedbacksData->this_month_total_feedbacks) * 100, 2);
        $feedbacksData->this_month_negative_count = $feedbacksData->this_month_total_feedbacks == 0  ? 0 :  round(($feedbacksData->this_month_negative_count / $feedbacksData->this_month_total_feedbacks) * 100, 2);

        return view('admin.dashboards.information', compact('userChart', 'projectChart', 'usersData', 'feedbacksData', 'verificationCount', 'announcementCount', 'thisMonthProjectCount'));
    }

    public function taskforceAdmin() {

    }

    public function index() {

        if (Auth::user()->user_role_id == 2) {
            return $this->informationAdmin();
        } else if (Auth::user()->user_role_id == 3) {
            return $this->informationAdmin();
        } else if (Auth::user()->user_role_id == 4) {
            return $this->informationAdmin();
        }
    }
}
