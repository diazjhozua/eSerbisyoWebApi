<?php

namespace App\Http\Controllers\Api;

use App\Events\ReportEvent;
use App\Events\ReportNotification;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;
use App\Http\Requests\RespondReportRequest;
use App\Http\Resources\ReportResource;
use App\Http\Resources\TypeResource;
use App\Models\Report;
use App\Models\Type;
use Carbon\Carbon;
use Storage;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::with('type')->where('user_id', auth('api')->user()->id)->orderBy('created_at','DESC')->get();
        return ReportResource::collection($reports)->additional(['success' => true]);
    }

    public function create()
    {
        $types = Type::where('model_type', 'Report')->get();
        return response()->json(['types' => TypeResource::collection($types)], 200);

    }

    public function store(ReportRequest $request)
    {
        $authReportCount = Report::whereDate('created_at', Carbon::today())->where('user_id', auth('api')->user()->id)->count();

        // if ($authReportCount > 3) {
        //     return response()->json(["message" => "You have already submitted to many report within this day, please comeback tommorow to submit another report"], 403);
        // }

        if($request->picture != ''){
            $fileName = uniqid().time().'.jpg';
            $filePath = 'reports/'.$fileName;
            Storage::disk('public')->put($filePath, base64_decode($request->picture));
            $report = Report::create(array_merge($request->getData(), ['status' => 'Pending', 'user_id' => auth('api')->user()->id,'picture_name' => $fileName,'file_path' => $filePath]));

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
}
