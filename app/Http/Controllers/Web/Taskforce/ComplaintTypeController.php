<?php

namespace App\Http\Controllers\Web\Taskforce;

use App\Http\Controllers\Controller;
use App\Http\Requests\ComplaintTypeRequest;
use App\Http\Requests\TaskforceReport\TypeReportRequest;
use App\Http\Resources\TypeResource;
use App\Models\Complaint;
use App\Models\Type;
use Carbon\Carbon;
use DB;
use Helper;
use Illuminate\Http\Request;
use Log;

class ComplaintTypeController extends Controller
{
    public function index()
    {
        $types = Type::withCount('complaints')->where('model_type', 'Complaint')->orderBy('created_at','DESC')->get();
        $types->add(new Type([ 'id' => 0, 'name' => 'Others', 'model_type' => 'Complaint', 'created_at' => now(), 'updated_at' => now(),
            'complaints_count' => Complaint::where('type_id', NULL)->count() ]));

        return view('admin.taskforce.complaint-types.index', compact('types'));
    }

    public function store(ComplaintTypeRequest $request)
    {
        $type = Type::create(array_merge($request->validated(), ['model_type' => 'Complaint']));
        $type->complaints_count = 0;
        return (new TypeResource($type))->additional(Helper::instance()->storeSuccess('complaint_type'));
    }

    public function show($id)
    {
        if ($id == 0) {
            $otherTotals = DB::table('complaints')
            ->selectRaw('count(*) as complaints_count')
            ->selectRaw("count(case when status = 'Pending' then 1 end) as pending_count")
            ->selectRaw("count(case when status = 'Noted' then 1 end) as noted_count")
            ->selectRaw("count(case when status = 'Invalid' then 1 end) as invalid_count")
            ->selectRaw("count(case when status = 'Ignored' then 1 end) as ignored_count")
            ->where('type_id', '=', NULL)
            ->first();

            $type = new Type([
                'id' => 0,
                'name' => 'Others',
                'model_type' => 'Complaint',
                'created_at' => now(),
                'updated_at' => now(),
                'pending_count' => $otherTotals->pending_count,
                'noted_count' => $otherTotals->noted_count,
                'invalid_count' => $otherTotals->invalid_count,
                'ignored_count' => $otherTotals->ignored_count,
                'complaints' => Complaint::where('type_id', '=', NULL)
                    ->orderBy('created_at', 'DESC')->get()
            ]);

        } else {
            $type = Type::with(['complaints' => function ($query) {
                $query->orderBy('created_at', 'DESC');
            }])->withCount(['complaints','complaints as pending_count' => function ($query) {
                $query->where('status', 'Pending');
            }, 'complaints as noted_count' => function ($query) {
                $query->where('status', 'Noted');
            }, 'complaints as invalid_count' => function ($query) {
                $query->where('status', 'Invalid');
            }, 'complaints as ignored_count' => function ($query) {
                $query->where('status', 'Ignored');
            }])->where('model_type', 'Complaint')->findOrFail($id);
        }

        return view('admin.taskforce.complaint-types.show', compact('type'));
    }

    public function edit($id)
    {
        if ($id == 0) { return response()->json(Helper::instance()->noEditAccess()); }
        $type = Type::where('model_type', 'Complaint')->findOrFail($id);
        return (new TypeResource($type))->additional(Helper::instance()->itemFound('complaint_type'));
    }

    public function update(ComplaintTypeRequest $request, $id)
    {
        if ($id == 0) { return response()->json(Helper::instance()->noUpdateAccess()); }
        $type = Type::withCount('complaints')->where('model_type', 'Complaint')->findOrFail($id);
        $type->fill($request->validated())->save();
        return (new TypeResource($type))->additional(Helper::instance()->updateSuccess('complaint_type'));
    }

    public function destroy($id)
    {
        if ($id == 0) { return response()->json(Helper::instance()->noDeleteAccess()); }
        $type = Type::where('model_type', 'Complaint')->findOrFail($id);
        Complaint::where('type_id', $type->id)->update(['type_id' => NULL, 'custom_type' => 'deleted type: '.$type->name]);
        $type->delete();
        return response()->json(Helper::instance()->destroySuccess('complaint_type'));
    }

    public function report($date_start,  $date_end, $sort_column, $sort_option)
    {
        try {
            $types = Type::withCount('complaints as count')
                ->where('model_type', 'Complaint')->orderBy('created_at','DESC')
                ->whereBetween('created_at', [$date_start, $date_end])
                ->orderBy($sort_column, $sort_option)
                ->get();
        } catch(\Illuminate\Database\QueryException $ex){}

        if ($types->isEmpty()) {
            $title = 'Complaint - No data';
            $description = 'No data';
            return view('errors.404Report', compact('title', 'description'));
        }

        $types->add(new Type([ 'id' => 0, 'name' => 'Others (Complaint w/o complaint type)', 'model_type' => 'Complaint', 'created_at' => now(), 'updated_at' => now(),
            'count' => Complaint::where('type_id', NULL)->count() ]));

        $title = 'Complaint Type Publish Report';
        $modelName = 'Complaint';

        return view('admin.taskforce.pdf.type', compact('title', 'modelName', 'types',
            'date_start', 'date_end', 'sort_column', 'sort_option'
        ));
    }

    public function reportShow($id, $date_start, $date_end, $sort_column, $sort_option, $status_option)
    {
        $complaints = null;
        try {
            $complaints = Complaint::with('type', 'defendants', 'complainants')
                ->whereBetween('created_at', [$date_start, $date_end])
                ->orderBy($sort_column, $sort_option)
                ->where(function($query) use ($status_option) {
                    if($status_option == 'all') {
                        return null;
                    } else {
                        return $query->where('status', '=', ucwords($status_option));
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

        if ($complaints == null) {
            $title = 'Report - No data';
            $description = 'No data';
            return view('errors.404Report', compact('title', 'description'));
        }

        $complaintsData = null;

        $complaintsData =  DB::table('complaints')
            ->selectRaw('count(*) as complaints_count')
            ->selectRaw("count(case when status = 'Pending' then 1 end) as pending_count")
            ->selectRaw("count(case when status = 'Denied' then 1 end) as denied_count")
            ->selectRaw("count(case when status = 'Approved' then 1 end) as approved_count")
            ->selectRaw("count(case when status = 'Resolved' then 1 end) as resolved_count")
            ->where('created_at', '>=', $date_start)
            ->where('created_at', '<=', $date_end)
            ->where(function($query) use ($status_option) {
                if($status_option == 'all') {
                    return null;
                } else {
                    return $query->where('status', '=', ucwords($status_option));
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

        $title = 'User Submitted Complaints Reports';
        $modelName = 'Complaint';

        return view('admin.taskforce.pdf.complaint', compact('title', 'modelName', 'complaints', 'complaintsData',
            'date_start', 'date_end', 'sort_column', 'sort_option', 'status_option'
        ));
    }

}
