<?php

namespace App\Http\Controllers\Api;

use App\Events\ReportEvent;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;
use App\Http\Resources\ReportResource;
use App\Http\Resources\TypeResource;
use App\Models\Report;
use App\Models\Type;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::with('type')->where('user_id', auth('api')->user()->id)->orderBy('id','DESC')->get();
        return ReportResource::collection($reports)->additional(['success' => true]);
    }

    public function create()
    {
        $types = Type::where('model_type', 'Report')->get();
        return response()->json(['types' => TypeResource::collection($types)], 200);
    }

    public function store(ReportRequest $request)
    {
        activity()->disableLogging();
        $authReportCount = Report::whereDate('created_at', Carbon::today())->where('user_id', auth('api')->user()->id)->count();

        // if ($authReportCount > 3) {
        //     return response()->json(["message" => "You have already submitted to many report within this day, please comeback tommorow to submit another report"], 403);
        // }

        if($request->picture != ''){
            $result = cloudinary()->uploadFile('data:image/jpeg;base64,'.$request->picture, ['folder' => env('CLOUDINARY_PATH', 'dev-barangay')]);
            $report = Report::create(array_merge($request->getData(), ['status' => 'Pending',
                'user_id' => auth('api')->user()->id,
                'picture_name' => $result->getPublicId(),
                'file_path' => $result->getPath(),
            ]));

        } else {
            $report = Report::create(array_merge($request->getData(), ['status' => 'Pending', 'user_id' => auth('api')->user()->id]));
        }

        event(new ReportEvent($report->load('type')));

        return (new ReportResource($report->load('type')))->additional(Helper::instance()->storeSuccess('report'));
    }

    public function show(Report $report)
    {
        if ($report->user_id == auth('api')->user()->id) {
            return (new ReportResource($report->load('type')))->additional(Helper::instance()->itemFound('report'));
        } else {
            return response()->json(["message" => "You can only view your reports."], 403);
        }
    }

    // get short analytics about the overall overview.
    public function getAnalytics()
    {
        $reportTypes = Type::withCount(['reports' => function($query){
            $query->where('created_at', '>=', date('Y-m-d',strtotime('first day of this month')))
            ->where('created_at', '<=', date('Y-m-d',strtotime('last day of this month')));
        }])
        ->where('model_type', 'Report')->orderBy('reports_count', 'DESC')->get();

        $trendingReports = Type::where('model_type', 'Report')->withCount('reports')->orderBy('reports_count', 'DESC')->limit(5)->get();

        $reports = Report::select('id', 'created_at')
            ->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('m'); // grouping by years
        });

        $userAverageReport = [];
        $userReport = [];

        foreach ($reports as $key => $value) {
            $yearList = [];
            $userReportCount = 0;

            foreach ($value as $userReportData) {
                $userReportCount ++;
                $year = Carbon::parse($userReportData->created_at)->format('Y');
                if (!in_array($year, $yearList)) {
                    array_push($yearList, $year);
                }
            }

            $userAverageReport[(int)$key] = round($userReportCount / count($yearList), 2);
        }

        for($i = 1; $i <= 12; $i++){
            if(!empty($userAverageReport[$i])){
                $userReport[$i] = $userAverageReport[$i];
            }else{
                $userReport[$i] = 0;
            }
        }
        return response()->json([
            'reportTypes' => $reportTypes,
            'userReport' => $userReport,
            'trendingReports' => $trendingReports,
        ], 200);
    }
}
