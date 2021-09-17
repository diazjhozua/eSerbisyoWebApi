<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReportTypeRequest;
use App\Http\Resources\ReportTypeResource;
use App\Models\Report;
use App\Models\ReportType;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ReportTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $report_types = ReportType::withCount('reports')->orderBy('reports_count', 'DESC')->get();

        $others = new ReportType([
            'id' => 0,
            'type' => 'Others',
            'created_at' => now(),
            'updated_at' => now(),
            'reports_count' => Report::where('report_type_id', '=', NULL)->count(),
        ]);

        $report_types->add($others);

        return response()->json([
            'success' => true,
            'report_types' => ReportTypeResource::collection($report_types)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReportTypeRequest $request)
    {
        $report_type = new ReportType();
        $report_type->type = $request->type;
        $report_type->save();
        $report_type->reports_count = 0;

        return response()->json([
            'success' => true,
            'message' => 'New report type created succesfully',
            'report_type' => new ReportTypeResource($report_type)
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
            if ($id == 0) {
                $report_type = new ReportType([
                    'id' => 0,
                    'type' => 'Others',
                    'created_at' => now(),
                    'updated_at' => now(),
                    'reports_count' => Report::where('report_type_id', '=', NULL)->count(),
                    'others' => Report::with('user')->orderBy('created_at', 'DESC')->where('report_type_id', '=', NULL)->get()
                ]);


            } else {
                $report_type = ReportType::with(['reports' => function($query){
                    $query->with('user');
                    $query->orderBy('created_at', 'DESC');
                }])->withCount('reports')->findOrFail($id);
            }

            return response()->json([
                'success' => true,
                'message' => 'Found reports type data',
                'report_type' => new ReportTypeResource($report_type)
            ]);

        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('report type'));
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
        try {
            if ($id == 0) {
                return response()->json(Helper::instance()->noEditAccess());
            }

            $report_type = ReportType::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Found report type data',
                'report_type' => new ReportTypeResource($report_type)
            ]);

        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('report type'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ReportTypeRequest $request, $id)
    {
        try {
            if ($id == 0) {
                return response()->json(Helper::instance()->noUpdateAccess());
            }
            $report_type = ReportType::withCount('reports')->findOrFail($id);
            $report_type->type = $request->type;
            $report_type->save();

            return response()->json([
                'success' => true,
                'message' => 'The report type is successfully updated',
                'report_type' => new ReportTypeResource($report_type)
            ]);

        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('report type'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($id == 0) {
            return response()->json(Helper::instance()->noDeleteAccess());
        }

        try {
            $report_type = ReportType::findOrFail($id);
            $report_type->delete();
            return response()->json([
                'success' => true,
                'message' => 'The report type is successfully deleted',
            ]);
        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('report type'));
        }
    }
}
