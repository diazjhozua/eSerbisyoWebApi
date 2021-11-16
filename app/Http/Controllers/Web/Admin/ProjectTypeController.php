<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectTypeRequest;
use App\Http\Requests\Report\ProjectReportRequest;
use App\Http\Requests\Report\TypeReportRequest;
use App\Http\Resources\TypeResource;
use App\Models\Project;
use App\Models\Type;
use Barryvdh\DomPDF\Facade as PDF;

class ProjectTypeController extends Controller
{
    public function index()
    {
        $types = Type::withCount('projects')->where('model_type', 'Project')->orderBy('created_at','DESC')->get();
        $types->add(new Type([ 'id' => 0, 'name' => '(Others) - Project without assigned project type', 'model_type' => 'Project', 'created_at' => now(), 'updated_at' => now(),
            'projects_count' => Project::where('type_id', NULL)->count() ]));

        return view('admin.information.project-types.index')->with('types', $types);
    }

    public function store(ProjectTypeRequest $request)
    {
        $type = Type::create(array_merge($request->validated(), ['model_type' => 'Project']));
        $type->projects_count = 0;
        return (new TypeResource($type))->additional(Helper::instance()->storeSuccess('project_type'));
    }

    public function show($id)
    {
        if ($id == 0) {
            $projects = Project::where('type_id', NULL)->orderBy('created_at', 'DESC')->get();
            $type = (new Type([ 'id' => 0, 'name' => '(Others) - Project w/o project type', 'model_type' => 'Project', 'created_at' => now(), 'updated_at' => now(),
            'projects_count' => $projects->count(), 'projects' => $projects ]));
        } else {  $type = Type::with('projects')->where('model_type', 'Project')->withCount('projects')->findOrFail($id); }

        return view('admin.information.project-types.show')->with('type', $type);
    }

    public function edit($id)
    {
        if ($id == 0) { return response()->json(Helper::instance()->noEditAccess()); }
        $type = Type::where('model_type', 'Project')->findOrFail($id);
        return (new TypeResource($type))->additional(Helper::instance()->itemFound('project_type'));
    }

    public function update(ProjectTypeRequest $request, $id)
    {
        if ($id == 0) { return response()->json(Helper::instance()->noUpdateAccess()); }
        $type = Type::withCount('projects')->where('model_type', 'Project')->findOrFail($id);
        $type->fill($request->validated())->save();
        return (new TypeResource($type))->additional(Helper::instance()->updateSuccess('project_type'));
    }

    public function destroy($id)
    {
        if ($id == 0) { return response()->json(Helper::instance()->noDeleteAccess()); }
        $type = Type::where('model_type', 'Project')->findOrFail($id);
        Project::where('type_id', $type->id)->update(['type_id' => NULL, 'custom_type' => 'deleted type: '.$type->name]);
        $type->delete();
        return response()->json(Helper::instance()->destroySuccess('project_type'));
    }

    public function report(TypeReportRequest $request) {
        $types = Type::withCount('projects as count')
            ->where('model_type', 'Project')->orderBy('created_at','DESC')
            ->whereBetween('created_at', [$request->date_start, $request->date_end])
            ->orderBy($request->sort_column, $request->sort_option)
            ->get();

        if ($types->isEmpty()) {
            return response()->json(['No data'], 404);
        }

        $types->add(new Type([ 'id' => 0, 'name' => 'Others (Project w/o project type)', 'model_type' => 'Project', 'created_at' => now(), 'updated_at' => now(),
            'count' => Project::where('type_id', NULL)->count() ]));

        $title = 'Project Type Publish Report';
        $modelName = 'Project';

        $pdf = PDF::loadView('admin.information.reports.type', compact('types', 'request', 'title', 'modelName'))->setOptions(['defaultFont' => 'sans-serif'])->setPaper('a4', 'landscape');
        return $pdf->stream();
    }

    public function reportShow(ProjectReportRequest $request, $typeID) {
        $projects = Project::with('type')
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

        if ($projects->isEmpty()) {
            return response()->json(['No data'], 404);
        }

        $pdf = PDF::loadView('admin.information.reports.project', compact('projects', 'request'))->setOptions(['defaultFont' => 'sans-serif'])->setPaper('a4', 'landscape');
        return $pdf->stream();
    }

}
