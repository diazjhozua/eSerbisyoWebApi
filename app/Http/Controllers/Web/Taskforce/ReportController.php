<?php

namespace App\Http\Controllers\Web\Taskforce;

use App\Http\Controllers\Controller;
use App\Http\Requests\RespondReportRequest;
use App\Http\Resources\ReportResource;
use App\Jobs\RespondReportJob;
use App\Models\Report;
use DB;
use Helper;

class ReportController extends Controller
{

    public function index()
    {

        $reportsData =  DB::table('reports')
        ->selectRaw('count(*) as reports_count')
        ->selectRaw("count(case when status = 'Pending' then 1 end) as pending_count")
        ->selectRaw("count(case when status = 'Noted' then 1 end) as noted_count")
        ->selectRaw("count(case when status = 'Invalid' then 1 end) as invalid_count")
        ->selectRaw("count(case when status = 'Ignored' then 1 end) as ignored_count")
        ->where('created_at', '>=', date('Y-m-d',strtotime('first day of this month')))
        ->where('created_at', '<=', date('Y-m-d',strtotime('last day of this month')))
        ->first();

        $reports = Report::with('type')->orderBy('created_at','DESC')->get();
        return view('admin.taskforce.reports.index', compact('reports', 'reportsData'));
    }

    public function show(Report $report)
    {
        if(request()->ajax()) {
            return (new ReportResource($report->load('type')))->additional(Helper::instance()->itemFound('report'));
        }
    }

    public function respond(RespondReportRequest $request, Report $report) {
        if(request()->ajax()) {
            $report->fill($request->validated())->save();

            $subject = $request->status == 'Noted' ? 'Your submitted report was noted by the barangay official' : 'Your submitted report was invalidated' ;

            dispatch(new RespondReportJob($report, $subject));
            return (new ReportResource($report->load('type')))->additional(Helper::instance()->noted('report'));
        }
    }
}
