<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ProjectTypeRequest;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\TypeResource;
use App\Models\Project;
use App\Models\Type;
use App\Helper\Helper;


class ProjectTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $types = Type::withCount('projects')->where('model_type', 'Project')->orderBy('created_at','DESC')->get();
        $types->add(new Type([ 'id' => 0, 'name' => 'Others (Project w/o ID)', 'model_type' => 'Project', 'created_at' => now(), 'updated_at' => now(),
            'projects_count' => Project::where('type_id', NULL)->count() ]));
        return TypeResource::collection($types)->additional(['success' => true]);
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
    public function store(ProjectTypeRequest $request)
    {
        $type = Type::create(array_merge($request->validated(), ['model_type' => 'Project']));
        $type->projects_count = 0;
        return (new TypeResource($type))->additional(Helper::instance()->storeSuccess('project_type'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if ($id == 0) {
            $projects = Project::where('type_id', NULL)->orderBy('created_at', 'DESC')->get();
            $type = (new Type([ 'id' => 0, 'name' => 'Others', 'model_type' => 'Project', 'created_at' => now(), 'updated_at' => now(),
            'projects_count' => $projects->count(), 'others' => $projects ]));
        } else {  $type = Type::with('projects')->where('model_type', 'Project')->withCount('projects')->findOrFail($id); }
        return (new TypeResource($type))->additional(Helper::instance()->itemFound('project_type'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if ($id == 0) { return response()->json(Helper::instance()->noEditAccess()); }
        $type = Type::where('model_type', 'Ordinance')->findOrFail($id);
        return (new TypeResource($type))->additional(Helper::instance()->itemFound('ordinance_type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectTypeRequest $request, $id)
    {
        if ($id == 0) { return response()->json(Helper::instance()->noUpdateAccess()); }
        $type = Type::withCount('projects')->where('model_type', 'Project')->findOrFail($id);
        $type->fill($request->validated())->save();
        return (new TypeResource($type))->additional(Helper::instance()->updateSuccess('project_type'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($id == 0) { return response()->json(Helper::instance()->noDeleteAccess()); }
        $type = Type::where('model_type', 'Project')->findOrFail($id);
        Project::where('type_id', $type->id)->update(['type_id' => NULL, 'custom_type' => 'deleted type: '.$type->name]);
        $type->delete();
        return response()->json(Helper::instance()->destroySuccess('project_type'));
    }
}
