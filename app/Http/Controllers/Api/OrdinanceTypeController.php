<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrdinanceTypeRequest;
use App\Http\Resources\TypeResource;
use App\Models\Ordinance;
use App\Models\Type;

class OrdinanceTypeController extends Controller
{
    public function index()
    {
        $types = Type::withCount('ordinances')->where('model_type', 'Ordinance')->orderBy('created_at','DESC')->get();
        $types->add(new Type([ 'id' => 0, 'name' => 'Others (Ordinance w/o ID)', 'model_type' => 'Ordinance', 'created_at' => now(), 'updated_at' => now(),
            'ordinances_count' => Ordinance::where('type_id', NULL)->count() ]));
        return TypeResource::collection($types)->additional(['success' => true]);
    }

    public function store(OrdinanceTypeRequest $request)
    {
        $type = Type::create(array_merge($request->validated(), ['model_type' => 'Ordinance']));
        $type->documents_count = 0;
        return (new TypeResource($type))->additional(Helper::instance()->storeSuccess('ordinance_type'));
    }

    public function show($id)
    {
        if ($id == 0) {
            $ordinances = Ordinance::where('type_id', NULL)->orderBy('created_at', 'DESC')->get();
            $type = (new Type([ 'id' => 0, 'name' => 'Others', 'model_type' => 'Ordinance', 'created_at' => now(), 'updated_at' => now(),
            'ordinances_count' => $ordinances->count(), 'others' => $ordinances ]));
        } else {  $type = Type::with('ordinances')->where('model_type', 'Ordinance')->withCount('ordinances')->findOrFail($id); }
        return (new TypeResource($type))->additional(Helper::instance()->itemFound('ordinance_type'));
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
}
