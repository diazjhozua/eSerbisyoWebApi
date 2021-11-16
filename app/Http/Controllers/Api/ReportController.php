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

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::with('type')->orderBy('created_at','DESC')->get();
        return ReportResource::collection($reports)->additional(['success' => true]);
    }

    public function create()
    {
        $types = Type::where('model_type', 'Report')->get();
        $urgencyClassification = $reportTypes = [ (object)[ "id" => 1, "type" => "Nonurgent"],(object) ["id" => 2,"type" => "Urgent"] ];
        return ['types' => TypeResource::collection($types), 'urgencyClassification' => $urgencyClassification, 'success' => true];
    }

    public function store(ReportRequest $request)
    {
        if($request->hasFile('picture')) {
            $fileName = time().'_'.$request->picture->getClientOriginalName();
            $filePath = $request->file('picture')->storeAs('reports', $fileName, 'public');
            $report = Report::create(array_merge($request->getData(), ['status' => 'Pending', 'user_id' => 2,'picture_name' => $fileName,'file_path' => $filePath]));
        } else { $report = Report::create(array_merge($request->getData(), ['status' => 'Pending', 'user_id' => 2])); }

        // event(new ReportNotification('This is our first broadcast message'));
        event(new ReportEvent($report->load('type')));

        return (new ReportResource($report->load('type')))->additional(Helper::instance()->storeSuccess('report'));
    }

    public function show(Report $report)
    {
        return (new ReportResource($report->load('type')))->additional(Helper::instance()->itemFound('report'));
    }

    public function respond(RespondReportRequest $request, Report $report) {
        $report->fill($request->validated())->save();
        return (new ReportResource($report->load('type')))->additional(Helper::instance()->noted('report'));
    }
}
