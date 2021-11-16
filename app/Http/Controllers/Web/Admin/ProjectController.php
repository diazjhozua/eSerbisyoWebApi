<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Http\Requests\Report\ProjectReportRequest;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\TypeResource;
use App\Models\Project;
use App\Models\Type;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade as PDF;

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
        return view('admin.information.projects.index', compact('projectsData', 'projects'));
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

    public function report(ProjectReportRequest $request) {
        $projects = Project::with('type')
            ->whereBetween('created_at', [$request->date_start, $request->date_end])
            ->orderBy($request->sort_column, $request->sort_option)
            ->get();

        if ($projects->isEmpty()) {
            return response()->json(['No data'], 404);
        }

        $pdf = PDF::loadView('admin.information.reports.project', compact('projects', 'request'))->setOptions(['defaultFont' => 'sans-serif'])->setPaper('a4', 'landscape');
        return $pdf->stream();
    }
}
