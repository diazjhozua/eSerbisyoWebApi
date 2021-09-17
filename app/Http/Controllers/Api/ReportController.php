<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;
use App\Http\Requests\RespondReportRequest;
use App\Http\Resources\ReportResource;
use App\Http\Resources\ReportTypeResource;
use App\Models\Report;
use App\Models\ReportType;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function respond(RespondReportRequest $request) {


        $report_date = "2021-09-13 13:52:09";
        $formatted_date = Carbon::now()->subMinutes(15)->toDateTimeString();


        if ($report_date <= $formatted_date) {
            dd('late');
        }

        $date = new DateTime();
        $date->modify('-15 minutes');
        $formatted_date = $date->format('Y-m-d H:i:s');

        dd('tite');

        dd($formatted_date);
        $report = Report::with('user', 'report_type')->find($request->id);

        if ($report->status == 1) {
            $oldStatus = $report->status;
            $report->status = $request->status;
            $report->admin_message = $request->admin_message;
            $report->save();

            return response()->json([
                'success' => true,
                'message' => Helper::instance()->respondMessage($oldStatus, $request->status, 'Report'),
                'report' => new ReportResource($report)
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'This report was validated already (Administrator can only validate )',
            ]);
        }

    }

    public function index()
    {
        dd(Storage::delete('public/reports/3deb3da6-422c-34a1-a64f-ccd4428e4204.jpg'));
        $reports = Report::with('report_type', 'user')->orderBy('created_at', 'DESC')->get();

        return response()->json([
            'success' => true,
            'reports' => ReportResource::collection($reports)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $report_types = ReportType::get();

        return response()->json([
            'success' => true,
            'reports' => ReportTypeResource::collection($report_types)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReportRequest $request)
    {
        $report = new Report();
        // $report->user_id = Auth::user()->id;
        $report->user_id = 2;
        $report->report_type_id = $request->report_type_id;
        $report->custom_type = $request->custom_type;
        $report->location_address = $request->location_address;
        $report->landmark = $request->landmark;
        $report->description = $request->description;
        $report->is_anonymous = $request->is_anonymous;
        $report->is_urgent = $request->is_urgent;
        $report->status = 1; //always pending when submitted

        //check if they want to update the pdf file
        if($request->hasFile('picture')) {
            $fileName = time().'_'.$request->picture->getClientOriginalName();
            $filePath = $request->file('picture')->storeAs('reports', $fileName, 'public');
            $report->picture_name = $fileName;
            $report->file_path = $filePath;
        }

        $report->save();

        return response()->json([
            'success' => true,
            'message' => 'New report submitted succesfully',
            'report' => new ReportResource($report->load('user', 'report_type'))
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $report = Report::with('user', 'report_type')->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Found report data',
                'report' => new ReportResource($report)
            ]);

        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('report'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ReportRequest $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
