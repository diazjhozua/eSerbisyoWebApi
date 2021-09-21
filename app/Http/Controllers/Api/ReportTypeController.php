<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReportTypeRequest;
use App\Http\Resources\TypeResource;
use App\Models\Report;
use App\Models\Type;

class ReportTypeController extends Controller
{
    public function index()
    {
        $types = Type::withCount('reports')->where('model_type', 'Report')->orderBy('created_at','DESC')->get();
        $types->add(new Type([ 'id' => 0, 'name' => 'Others', 'model_type' => 'Report', 'created_at' => now(), 'updated_at' => now(),
            'reports_count' => Report::where('type_id', NULL)->count() ]));
        return TypeResource::collection($types)->additional(['success' => true]);
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
            $reports = Report::where('type_id', NULL)->orderBy('created_at', 'DESC')->get();
            $type = (new Type([ 'id' => 0, 'name' => 'Others', 'model_type' => 'Reports', 'created_at' => now(), 'updated_at' => now(),
            'reports_count' => $reports->count(), 'others' => $reports ]));
        } else {  $type = Type::with('reports')->where('model_type', 'Report')->withCount('reports')->findOrFail($id); }
        return (new TypeResource($type))->additional(Helper::instance()->itemFound('report_type'));
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
}
