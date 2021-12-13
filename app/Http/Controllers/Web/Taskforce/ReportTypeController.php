<?php

namespace App\Http\Controllers\Web\Taskforce;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportTypeRequest;
use App\Http\Resources\TypeResource;
use App\Models\Report;
use App\Models\Type;
use DB;
use Helper;

class ReportTypeController extends Controller
{
    public function index()
    {
        $types = Type::withCount('reports')->where('model_type', 'Report')->orderBy('created_at','DESC')->get();
        $types->add(new Type([ 'id' => 0, 'name' => 'Others', 'model_type' => 'Report', 'created_at' => now(), 'updated_at' => now(),
            'reports_count' => Report::where('type_id', NULL)->count() ]));

        return view('admin.taskforce.report-types.index', compact('types'));
    }

    public function store(ReportTypeRequest $request)
    {
        $type = Type::create(array_merge($request->validated(), ['model_type' => 'Report']));
        $type->reports_count = 0;
        return (new TypeResource($type))->additional(Helper::instance()->storeSuccess('report_type'));
    }

    public function show($id)
    {
        if ($id == 0) {
            $otherTotals = DB::table('reports')
            ->selectRaw('count(*) as reports_count')
            ->selectRaw("count(case when status = 'Pending' then 1 end) as pending_count")
            ->selectRaw("count(case when status = 'Noted' then 1 end) as noted_count")
            ->selectRaw("count(case when status = 'Invalid' then 1 end) as invalid_count")
            ->selectRaw("count(case when status = 'Ignored' then 1 end) as ignored_count")
            ->where('type_id', '=', NULL)
            ->first();

            $type = new Type([
                'id' => 0,
                'name' => 'Others',
                'model_type' => 'Report',
                'created_at' => now(),
                'updated_at' => now(),
                'pending_count' => $otherTotals->pending_count,
                'noted_count' => $otherTotals->noted_count,
                'invalid_count' => $otherTotals->invalid_count,
                'ignored_count' => $otherTotals->ignored_count,
                'reports' => Report::where('type_id', '=', NULL)
                    ->orderBy('created_at', 'DESC')->get()
            ]);

        } else {

            $type = Type::with(['reports' => function ($query) {
                $query->orderBy('created_at', 'DESC');
            }])->withCount(['reports','reports as pending_count' => function ($query) {
                $query->where('status', 'Pending');
            }, 'reports as noted_count' => function ($query) {
                $query->where('status', 'Noted');
            }, 'reports as invalid_count' => function ($query) {
                $query->where('status', 'Invalid');
            }, 'reports as ignored_count' => function ($query) {
                $query->where('status', 'Ignored');
            }])->where('model_type', 'Report')->findOrFail($id);

        }
        return view('admin.taskforce.report-types.show', compact('type'));
    }

    public function edit($id)
    {
        if ($id == 0) { return response()->json(Helper::instance()->noEditAccess()); }
        $type = Type::where('model_type', 'Report')->findOrFail($id);
        return (new TypeResource($type))->additional(Helper::instance()->itemFound('report_type'));
    }

    public function update(ReportTypeRequest $request, $id)
    {
        if ($id == 0) { return response()->json(Helper::instance()->noUpdateAccess()); }
        $type = Type::withCount('reports')->where('model_type', 'Report')->findOrFail($id);
        $type->fill($request->validated())->save();
        return (new TypeResource($type))->additional(Helper::instance()->updateSuccess('report_type'));
    }

    public function destroy($id)
    {
        if ($id == 0) { return response()->json(Helper::instance()->noDeleteAccess()); }
        $type = Type::where('model_type', 'Report')->findOrFail($id);
        Report::where('type_id', $type->id)->update(['type_id' => NULL, 'custom_type' => 'deleted type: '.$type->name]);
        $type->delete();
        return response()->json(Helper::instance()->destroySuccess('report_type'));
    }

    public function report($date_start,  $date_end, $sort_column, $sort_option)
    {
        try {
            $types = Type::withCount('reports as count')
                ->where('model_type', 'Report')->orderBy('created_at','DESC')
                ->whereBetween('created_at', [$date_start, $date_end])
                ->orderBy($sort_column, $sort_option)
                ->get();
        } catch(\Illuminate\Database\QueryException $ex){}

        if ($types->isEmpty()) {
            $title = 'Report - No data';
            $description = 'No data';
            return view('errors.404Report', compact('title', 'description'));
        }

        $types->add(new Type([ 'id' => 0, 'name' => 'Others (Report w/o report type)', 'model_type' => 'Report', 'created_at' => now(), 'updated_at' => now(),
            'count' => Report::where('type_id', NULL)->count() ]));

        $title = 'Report Type Publish Report';
        $modelName = 'Report';

        return view('admin.taskforce.pdf.type', compact('title', 'modelName', 'types',
            'date_start', 'date_end', 'sort_column', 'sort_option'
        ));
    }

    public function reportShow($id, $date_start,  $date_end, $sort_column, $sort_option, $classification_option, $status_option)
    {
        $reports = null;
        try {
            $reports = Report::with('type')
                ->whereBetween('created_at', [$date_start, $date_end])
                ->orderBy($sort_column, $sort_option)
                ->where(function($query) use ($classification_option, $status_option) {
                    if($classification_option == 'all' && $status_option == 'all') {
                        return null;
                    } elseif ($classification_option == 'all' && $status_option != 'all') {
                        return $query->where('status', '=', ucwords($status_option));
                    } elseif ($classification_option != 'all' && $status_option == 'all') {
                        return $query->where('urgency_classification', '=', ucwords($classification_option));
                    } else {
                        return $query->where('status', '=', ucwords($status_option))
                        ->where('urgency_classification', '=', ucwords($classification_option));
                    }
                })
                ->where(function($query) use ($id) {
                if ($id == 0) {
                    return $query->where('type_id', NULL);
                }else {
                    return $query->where('type_id', $id);
                }
                })->get();
        } catch(\Illuminate\Database\QueryException $ex){}

        if ($reports == null) {
            $title = 'Report - No data';
            $description = 'No data';
            return view('errors.404Report', compact('title', 'description'));
        }

        $reportsData = null;

        $reportsData =  DB::table('reports')
            ->selectRaw('count(*) as reports_count')
            ->selectRaw("count(case when status = 'Pending' then 1 end) as pending_count")
            ->selectRaw("count(case when status = 'Ignored' then 1 end) as ignored_count")
            ->selectRaw("count(case when status = 'Invalid' then 1 end) as invalid_count")
            ->selectRaw("count(case when status = 'Noted' then 1 end) as noted_count")
            ->selectRaw("count(case when urgency_classification = 'Nonurgent' then 1 end) as positive_count")
            ->selectRaw("count(case when urgency_classification = 'Urgent' then 1 end) as neutral_count")
            ->where('created_at', '>=', $date_start)
            ->where('created_at', '<=', $date_end)
            ->where(function($query) use ($classification_option, $status_option) {
                if($classification_option == 'all' && $status_option == 'all') {
                    return null;
                } elseif ($classification_option == 'all' && $status_option != 'all') {
                    return $query->where('status', '=', ucwords($status_option));
                } elseif ($classification_option != 'all' && $status_option == 'all') {
                    return $query->where('urgency_classification', '=', ucwords($classification_option));
                } else {
                    return $query->where('status', '=', ucwords($status_option))
                    ->where('urgency_classification', '=', ucwords($classification_option));
                }
            })
            ->where(function($query) use ($id) {
                if ($id == 0) {
                    return $query->where('type_id', NULL);
                }else {
                    return $query->where('type_id', $id);
                }
                })
            ->first();

        $title = 'User Submitted Reports';
        $modelName = 'Report';

        return view('admin.taskforce.pdf.report', compact('title', 'modelName', 'reports', 'reportsData',
            'date_start', 'date_end', 'sort_column', 'sort_option', 'classification_option', 'status_option'
        ));
    }

}
