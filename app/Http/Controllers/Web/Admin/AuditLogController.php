<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Report\AuditLogReportRequest;
use Auth;
use DB;
use Barryvdh\DomPDF\Facade as PDF;
use Spatie\Activitylog\Models\Activity;

class AuditLogController extends Controller
{
    public function index() {
        $firstDayMonth = date('Y-m-d',strtotime('first day of this month'));
        $lastDayMonth = date('Y-m-d',strtotime('last day of this month'));


        if (Auth::user()->user_role_id == 1) {

            $logs = Activity::orderBy('created_at', 'DESC')->get();

            $logsData =  DB::table('activity_log')
            ->selectRaw("count(case when event = 'created' then 1 end) as this_month_created_count")
            ->selectRaw("count(case when event = 'updated' then 1 end) as this_month_updated_count")
            ->selectRaw("count(case when event = 'deleted' then 1 end) as this_month_deleted_count")
            ->where('created_at', '>=', $firstDayMonth)
            ->where('created_at', '<=', $lastDayMonth)
            ->first();

            $info = '
                The list shows the activity made by the all of the admin and staff in this application.
            ';
        }

        if (Auth::user()->user_role_id == 2) {

            $logs = Activity::where('log_name', 'information')->orderBy('created_at', 'DESC')->get();

            $logsData =  DB::table('activity_log')
            ->selectRaw("count(case when event = 'created' then 1 end) as this_month_created_count")
            ->selectRaw("count(case when event = 'updated' then 1 end) as this_month_updated_count")
            ->selectRaw("count(case when event = 'deleted' then 1 end) as this_month_deleted_count")
            ->whereRaw("log_name = 'information'")
            ->where('created_at', '>=', $firstDayMonth)
            ->where('created_at', '<=', $lastDayMonth)
            ->first();

            $info = '
                The list shows the activity made by the admin and staff of the information team or any resident/biker who is accessing the functionality that is in the scope of your role. Every data changes or created made
                by the any information team, will be tracked here. You can determine here if the specific staff has made a suspicious activity in the system.
                The following models are tracked: Documents, Ordinances, Projects, Announcements, Announcement Picture, Types, Users, and Feedbacks (Only when the staff responded to the feedback).
                Feedback creation logs was intentionally disable to hide the author name to preserve their confidentiality (It means that only the admin responded to feedback would be audited).
            ';
        }

        if (Auth::user()->user_role_id == 3) {

            $logs = Activity::where('log_name', 'certificate')->orderBy('created_at', 'DESC')->get();

            $logsData =  DB::table('activity_log')
            ->selectRaw("count(case when event = 'created' then 1 end) as this_month_created_count")
            ->selectRaw("count(case when event = 'updated' then 1 end) as this_month_updated_count")
            ->selectRaw("count(case when event = 'deleted' then 1 end) as this_month_deleted_count")
            ->whereRaw("log_name = 'certificate'")
            ->where('created_at', '>=', $firstDayMonth)
            ->where('created_at', '<=', $lastDayMonth)
            ->first();

            $info = '
                The list shows the activity made by the admin and staff of the certificate team or any resident/biker who is accessing the functionality that is in the scope of your role. Every data changes or created made
                by the any certificate team, will be tracked here. You can determine here if the specific staff has made a suspicious activity in the system.
                The following models are tracked: Bikers Application, Certificate, and Certificate Request.
            ';
        }

        if (Auth::user()->user_role_id == 4) {

            $logs = Activity::where('log_name', 'taskforce')->orderBy('created_at', 'DESC')->get();

            $logsData =  DB::table('activity_log')
            ->selectRaw("count(case when event = 'created' then 1 end) as this_month_created_count")
            ->selectRaw("count(case when event = 'updated' then 1 end) as this_month_updated_count")
            ->selectRaw("count(case when event = 'deleted' then 1 end) as this_month_deleted_count")
            ->whereRaw("log_name = 'taskforce'")
            ->where('created_at', '>=', $firstDayMonth)
            ->where('created_at', '<=', $lastDayMonth)
            ->first();

            $info = '
                The list shows the activity made by the admin and staff of the information team or any resident/biker who is accessing the functionality that is in the scope of your role. Every data changes or created made
                by the any information team, will be tracked here. You can determine here if the specific staff has made a suspicious activity in the system.
                The following models are tracked: Reports, Complaints, Missing Person, and Missing Item (Only when the staff responded to the report).
                Report creation logs was intentionally disable to hide the author name to preserve their confidentiality (It means that only the admin responded to complaint would be audited).
            ';

        }


        return view('admin.auditLog.index', compact('logs', 'info', 'logsData'));
    }

    public function report(AuditLogReportRequest $request) {

        if (Auth::user()->user_role_id == 2) {
            $logs = Activity::whereBetween('created_at', [$request->date_start, $request->date_end])
                ->orderBy($request->sort_column, $request->sort_option)
                ->where('log_name', '=', 'information')
                ->get();


            if ($logs->isEmpty()) {
                return response()->json(['No data'], 404);
            }

            $logsData =  DB::table('activity_log')
                ->selectRaw("count(case when event = 'created' then 1 end) as created_count")
                ->selectRaw("count(case when event = 'updated' then 1 end) as updated_count")
                ->selectRaw("count(case when event = 'deleted' then 1 end) as deleted_count")
                ->whereRaw("log_name = 'information'")
                ->where('created_at', '>=', $request->date_start)
                ->where('created_at', '<=', $request->date_end)
                ->first();
        }

        if (Auth::user()->user_role_id == 3) {
            $logs = Activity::whereBetween('created_at', [$request->date_start, $request->date_end])
                ->orderBy($request->sort_column, $request->sort_option)
                ->where('log_name', '=', 'certificate')
                ->get();

            if ($logs->isEmpty()) {
                return response()->json(['No data'], 404);
            }

            $logsData =  DB::table('activity_log')
                ->selectRaw("count(case when event = 'created' then 1 end) as created_count")
                ->selectRaw("count(case when event = 'updated' then 1 end) as updated_count")
                ->selectRaw("count(case when event = 'deleted' then 1 end) as deleted_count")
                ->whereRaw("log_name = 'certificate'")
                ->where('created_at', '>=', $request->date_start)
                ->where('created_at', '<=', $request->date_end)
                ->first();
        }

        if (Auth::user()->user_role_id == 4) {
            $logs = Activity::whereBetween('created_at', [$request->date_start, $request->date_end])
                ->orderBy($request->sort_column, $request->sort_option)
                ->where('log_name', '=', 'taskforce')
                ->get();

            if ($logs->isEmpty()) {
                return response()->json(['No data'], 404);
            }

            $logsData =  DB::table('activity_log')
                ->selectRaw("count(case when event = 'created' then 1 end) as created_count")
                ->selectRaw("count(case when event = 'updated' then 1 end) as updated_count")
                ->selectRaw("count(case when event = 'deleted' then 1 end) as deleted_count")
                ->whereRaw("log_name = 'taskforce'")
                ->where('created_at', '>=', $request->date_start)
                ->where('created_at', '<=', $request->date_end)
                ->first();
        }

        // $logs = Activity::whereBetween('created_at', [$request->date_start, $request->date_end])
        //     ->orderBy($request->sort_column, $request->sort_option)
        //     ->get();


        // if ($logs->isEmpty()) {
        //     return response()->json(['No data'], 404);
        // }

        // $logsData =  DB::table('activity_log')
        //     ->selectRaw("count(case when event = 'created' then 1 end) as created_count")
        //     ->selectRaw("count(case when event = 'updated' then 1 end) as updated_count")
        //     ->selectRaw("count(case when event = 'deleted' then 1 end) as deleted_count")
        //     ->whereRaw("log_name = 'information'")
        //     ->where('created_at', '>=', $request->date_start)
        //     ->where('created_at', '<=', $request->date_end)
        //     ->first();

        $pdf = PDF::loadView('admin.information.reports.activityLog', compact('logs', 'request', 'logsData'))->setOptions(['defaultFont' => 'sans-serif'])->setPaper('a4', 'landscape');
        return $pdf->stream();
    }



}
