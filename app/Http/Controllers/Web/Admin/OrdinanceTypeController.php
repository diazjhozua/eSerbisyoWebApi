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


    public function report($date_start, $date_end, $sort_column, $sort_option) {
        $title = 'Report - No data';
        $description = 'No data';
        try {
           
            $types = Type::withCount('ordinances as count')
            ->where('model_type', 'Ordinance')->orderBy('created_at','DESC')
            ->whereBetween('created_at', [$date_start, $date_end])
            ->orderBy($sort_column, $sort_option)
            ->get();
    
            $types->add(new Type([ 'id' => 0, 'name' => 'Others (Ordinance w/o ordinance type)', 'model_type' => 'Ordinance', 'created_at' => now(), 'updated_at' => now(),
            'count' => Ordinance::where('type_id', NULL)->count() ]));
    
            } catch(\Illuminate\Database\QueryException $ex){
                return view('errors.404Report', compact('title', 'description'));
            }
            if ($types->isEmpty()) {
                return view('errors.404Report', compact('title', 'description'));
            }

                  

        $title = 'Ordinance Type Publish Report';
        $modelName = 'Ordinance';

        return view('admin.information.pdf.ordinanceTypes', compact('title', 'modelName', 'types',
            'date_start', 'date_end', 'sort_column', 'sort_option'

        ));
    }

    public function reportShow($date_start, $date_end, $sort_column, $sort_option, $type_id) {

        $title = 'Report - No data';
        $description = 'No data';

        try {
            $ordinances = Ordinance::with('type')
            ->whereBetween('created_at', [$date_start, $date_end])
            ->orderBy($sort_column, $sort_option)
            ->where(function($query) use ($type_id) {
                if ($type_id == 0) {
                    return $query->where('type_id', NULL);
                }else {
                    return $query->where('type_id', $type_id);
                }
            })
            ->get();
    
            } catch(\Illuminate\Database\QueryException $ex){
                return view('errors.404Report', compact('title', 'description'));
            }
            
            if ($ordinances->isEmpty()) {
                return view('errors.404Report', compact('title', 'description'));
            }

        $type = Type::find($type_id);
        $title = 'Ordinance Type Reports';
        $modelName =  $type_id == 0 ? 'Others/Deleted' : $type->name;

        return view('admin.information.pdf.ordinances', compact('title','modelName', 'ordinances',
        'date_start', 'date_end', 'sort_column', 'sort_option'

    ));

        // $pdf = PDF::loadView('admin.information.reports.ordinance', compact('ordinances', 'request'))->setOptions(['defaultFont' => 'sans-serif'])->setPaper('a4', 'landscape');
        // return $pdf->stream();
    }
}
