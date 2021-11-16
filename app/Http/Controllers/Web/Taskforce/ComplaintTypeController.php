<?php

namespace App\Http\Controllers\Web\Taskforce;

use App\Http\Controllers\Controller;
use App\Http\Requests\ComplaintTypeRequest;
use App\Http\Resources\TypeResource;
use App\Models\Complaint;
use App\Models\Type;
use Helper;
use Illuminate\Http\Request;

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
            $complaints = Complaint::where('type_id', NULL)->orderBy('created_at', 'DESC')->get();
            $type = (new Type([ 'id' => 0, 'name' => 'Others', 'model_type' => 'Complaint', 'created_at' => now(), 'updated_at' => now(),
            'complaints_count' => $complaints->count(), 'others' => $complaints ]));
        } else {  $type = Type::with('complaints')->where('model_type', 'Complaint')->withCount('complaints')->findOrFail($id); }
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
}
