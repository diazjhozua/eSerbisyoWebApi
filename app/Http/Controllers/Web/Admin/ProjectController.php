<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\TypeResource;
use App\Models\Project;
use App\Models\Type;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{

    public function index()
    {
        $firstDayYear = date('Y-m-d', strtotime('first day of january this year'));
        $lastDateYear = date('Y-m-d', strtotime('first day of december this year'));
        $firstDayMonth = date('Y-m-d',strtotime('first day of this month'));
        $lastDayMonth = date('Y-m-d',strtotime('last day of this month'));

        $projectsData =  DB::table('projects')
        ->selectRaw("count(case when created_at >='". $firstDayYear ."' AND created_at <='".$lastDateYear."' then 1 end) as this_year_count")
        ->selectRaw("count(case when created_at >='". $firstDayMonth ."' AND created_at <='".$lastDayMonth."' then 1 end) as this_month_count")
        ->selectRaw("count(case when DATE(created_at) = CURDATE() then 1 end) as this_day_count")
        ->first();

        $projects = Project::with('type')->orderBy('created_at','DESC')->get();
        return view('admin.projects.index', compact('projectsData', 'projects'));
    }

    public function create()
    {
        $types = Type::where('model_type', 'Project')->get();
        return ['types' => TypeResource::collection($types), 'success' => true];
    }

    public function store(ProjectRequest $request)
    {
        $fileName = time().'_'.$request->pdf->getClientOriginalName();
        $filePath = $request->file('pdf')->storeAs('projects', $fileName, 'public');
        $project = Project::create(array_merge($request->getData(), ['pdf_name' => $fileName,'file_path' => $filePath]));
        return (new ProjectResource($project->load('type')))->additional(Helper::instance()->storeSuccess('project'));
    }

    public function edit(Project $project)
    {
        $types = Type::where('model_type', 'Project')->get();
        return (new ProjectResource($project->load('type')))->additional(array_merge(['types' => TypeResource::collection($types)],Helper::instance()->itemFound('project')));
    }

    public function update(ProjectRequest $request, Project $project)
    {
        if($request->hasFile('pdf')) {
            Storage::delete('public/projects/'. $project->pdf_name);
            $fileName = time().'_'.$request->pdf->getClientOriginalName();
            $filePath = $request->file('pdf')->storeAs('projects', $fileName, 'public');
            $project->fill(array_merge($request->getData(), ['pdf_name' => $fileName,'file_path' => $filePath]))->save();
        } else { $project->fill(array_merge($request->getData(), ['custom_type' => NULL]))->save(); }
        return (new ProjectResource($project->load('type')))->additional(Helper::instance()->updateSuccess('project'));
    }

    public function destroy(Project $project)
    {
        Storage::delete('public/projects/'. $project->pdf_name);
        $project->delete();
        return response()->json(Helper::instance()->destroySuccess('project'));
    }
}
