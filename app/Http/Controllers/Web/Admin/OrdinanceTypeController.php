<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrdinanceTypeRequest;
use App\Http\Requests\Report\OrdinanceReportRequest;
use App\Http\Requests\Report\TypeReportRequest;
use App\Http\Resources\TypeResource;
use App\Models\Ordinance;
use App\Models\Type;
use Barryvdh\DomPDF\Facade as PDF;

class OrdinanceTypeController extends Controller
{
    public function index()
    {
        $types = Type::withCount('ordinances')->where('model_type', 'Ordinance')->orderBy('created_at','DESC')->get();
        $types->add(new Type([ 'id' => 0, 'name' => 'Others (Ordinance w/o ordinance type)', 'model_type' => 'Ordinance', 'created_at' => now(), 'updated_at' => now(),
            'ordinances_count' => Ordinance::where('type_id', NULL)->count() ]));

        return view('admin.information.ordinance-types.index')->with('types', $types);
    }

    public function store(OrdinanceTypeRequest $request)
    {
        $type = Type::create(array_merge($request->validated(), ['model_type' => 'Ordinance']));
        $type->ordinances_count = 0;
        return (new TypeResource($type))->additional(Helper::instance()->storeSuccess('ordinance_type'));
    }

    public function show($id)
    {
        if ($id == 0) {
            $ordinances = Ordinance::where('type_id', NULL)->orderBy('created_at', 'DESC')->get();
            $type = (new Type([ 'id' => 0, 'name' => 'Ordinance w/o ordinance type', 'model_type' => 'Ordinance', 'created_at' => now(), 'updated_at' => now(),
            'ordinances_count' => $ordinances->count(), 'ordinances' => $ordinances ]));
        } else {  $type = Type::with('ordinances')->where('model_type', 'Ordinance')->withCount('ordinances')->findOrFail($id); }

        return view('admin.information.ordinance-types.show')->with('type', $type);
    }

    public function edit($id)
    {
        if ($id == 0) { return response()->json(Helper::instance()->noEditAccess()); }
        $type = Type::where('model_type', 'Ordinance')->findOrFail($id);
        return (new TypeResource($type))->additional(Helper::instance()->itemFound('ordinance_type'));
    }

    public function update(OrdinanceTypeRequest $request, $id)
    {
        if ($id == 0) { return response()->json(Helper::instance()->noUpdateAccess()); }
        $type = Type::withCount('ordinances')->where('model_type', 'Ordinance')->findOrFail($id);
        $type->fill($request->validated())->save();
        return (new TypeResource($type))->additional(Helper::instance()->updateSuccess('ordinance_type'));
    }

    public function destroy($id)
    {
        if ($id == 0) { return response()->json(Helper::instance()->noDeleteAccess()); }
        $type = Type::where('model_type', 'Ordinance')->findOrFail($id);
        Ordinance::where('type_id', $type->id)->update(['type_id' => NULL, 'custom_type' => 'deleted type: '.$type->name]);
        $type->delete();
        return response()->json(Helper::instance()->destroySuccess('ordinance_type'));
    }


    public function report(TypeReportRequest $request) {
        $types = Type::withCount('ordinances as count')
            ->where('model_type', 'Ordinance')->orderBy('created_at','DESC')
            ->whereBetween('created_at', [$request->date_start, $request->date_end])
            ->orderBy($request->sort_column, $request->sort_option)
            ->get();

        if ($types->isEmpty()) {
            return response()->json(['No data'], 404);
        }

        $types->add(new Type([ 'id' => 0, 'name' => 'Others (Ordinance w/o ordinance type)', 'model_type' => 'Ordinance', 'created_at' => now(), 'updated_at' => now(),
            'count' => Ordinance::where('type_id', NULL)->count() ]));

        $title = 'Ordinance Type Publish Report';
        $modelName = 'Ordinance';

        $pdf = PDF::loadView('admin.information.reports.type', compact('types', 'request', 'title', 'modelName'))->setOptions(['defaultFont' => 'sans-serif'])->setPaper('a4', 'landscape');
        return $pdf->stream();
    }

    public function reportShow(OrdinanceReportRequest $request, $typeID) {
        $ordinances = Ordinance::with('type')
            ->whereBetween('created_at', [$request->date_start, $request->date_end])
            ->orderBy($request->sort_column, $request->sort_option)
            ->where(function($query) use ($typeID) {
                if ($typeID == 0) {
                    return $query->where('type_id', NULL);
                }else {
                    return $query->where('type_id', $typeID);
                }
            })
            ->get();

        if ($ordinances->isEmpty()) {
            return response()->json(['No data'], 404);
        }

        $pdf = PDF::loadView('admin.information.reports.ordinance', compact('ordinances', 'request'))->setOptions(['defaultFont' => 'sans-serif'])->setPaper('a4', 'landscape');
        return $pdf->stream();
    }
}
