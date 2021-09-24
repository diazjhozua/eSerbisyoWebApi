<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Helper\Helper;
use Illuminate\Http\Request;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\TypeResource;
use App\Models\Project;
use App\Models\Type;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;
class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $projects = Project::with('type')->orderBy('created_at','DESC')->get();
        return ProjectResource::collection($projects)->additional(['success' => true]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = Type::where('model_type', 'Project')->get();
        return ['types' => TypeResource::collection($types), 'success' => true];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectRequest $request)
    {

        $fileName = time().'_'.$request->pdf->getClientOriginalName();
        $filePath = $request->file('pdf')->storeAs('projects', $fileName, 'public');
        $project = Project::create(array_merge($request->getData(), ['pdf_name' => $fileName,'file_path' => $filePath]));
        return (new ProjectResource($project->load('type')))->additional(Helper::instance()->storeSuccess('project'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        return (new ProjectResource($project->load('type')))->additional(Helper::instance()->itemFound('project'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        $types = Type::where('model_type', 'Project')->get();
        return (new ProjectResource($project->load('type')))->additional(array_merge(['types' => TypeResource::collection($types)],Helper::instance()->itemFound('project')));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectRequest $request, Project $project)
    {
        if($request->hasFile('pdf')) {
            Storage::delete('public/projects/'. $project->pdf_name);
            $fileName = time().'_'.$request->pdf->getClientOriginalName();
            $filePath = $request->file('pdf')->storeAs('projects', $fileName, 'public');
            $project->fill(array_merge($request->getData(), ['custom_type' => NULL,'pdf_name' => $fileName,'file_path' => $filePath]))->save();
        } else { $project->fill(array_merge($request->getData(), ['custom_type' => NULL]))->save(); }
        return (new ProjectResource($project->load('type')))->additional(Helper::instance()->updateSuccess('project'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        Storage::delete('public/projects/'. $project->pdf_name);
        $project->delete();
        return response()->json(Helper::instance()->destroySuccess('project'));
    }
}
