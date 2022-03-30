<?php

namespace App\Http\Controllers\Web\Admin;

use App;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Http\Requests\Report\ProjectReportRequest;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\TypeResource;
use App\Jobs\SendNotificationJob;
use App\Models\Project;
use App\Models\Type;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade as PDF;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProjectController extends Controller
{

    public function index()
    {
        $firstDayYear = date('Y-m-d', strtotime('first day of january this year'));
        $lastDateYear = date('Y-m-d', strtotime('first day of december this year'));
        $firstDayMonth = date('Y-m-d',strtotime('first day of this month'));
        $lastDayMonth = date('Y-m-d',strtotime('last day of this month'));

        if (App::environment('production')) {
            $projectsData =  DB::table('projects')
                ->selectRaw("count(case when created_at >='". $firstDayYear ."' AND created_at <='".$lastDateYear."' then 1 end) as this_year_count")
                ->selectRaw("count(case when created_at >='". $firstDayMonth ."' AND created_at <='".$lastDayMonth."' then 1 end) as this_month_count")
                ->selectRaw("count(case when DATE(created_at) = CURRENT_DATE then 1 end) as this_day_count")
                ->first();
        } else {
            $projectsData =  DB::table('projects')
                ->selectRaw("count(case when created_at >='". $firstDayYear ."' AND created_at <='".$lastDateYear."' then 1 end) as this_year_count")
                ->selectRaw("count(case when created_at >='". $firstDayMonth ."' AND created_at <='".$lastDayMonth."' then 1 end) as this_month_count")
                ->selectRaw("count(case when DATE(created_at) = CURDATE() then 1 end) as this_day_count")
                ->first();
        }

        $projects = Project::with('type')->orderBy('id','DESC')->get();
        return view('admin.information.projects.index', compact('projectsData', 'projects'));
    }

    public function create()
    {
        $types = Type::where('model_type', 'Project')->get();
        return ['types' => TypeResource::collection($types), 'success' => true];
    }

    public function store(ProjectRequest $request)
    {
        $fileName = uniqid().'-'.time();
        $result = $request->file('pdf')->storeOnCloudinaryAs(env('CLOUDINARY_PATH', 'dev-barangay'), $fileName);
        $project = Project::create(array_merge($request->getData(), ['pdf_name' => $result->getPublicId(), 'file_path' => $result->getPath()]));

        dispatch(
            new SendNotificationJob(
                User::where('is_subscribed', 'Yes')->get(), "New project uploaded",
                "New project ".$project->name." has been uploaded. ", $project->id, "App\Models\Project",
        ));

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
            Cloudinary::destroy($project->pdf_name);
            $fileName = uniqid().'-'.time();
            $result = $request->file('pdf')->storeOnCloudinaryAs(env('CLOUDINARY_PATH', 'dev-barangay'), $fileName);
            $project->fill(array_merge($request->getData(), ['pdf_name' => $result->getPublicId(),'file_path' => $result->getPath()]))->save();
        } else { $project->fill(array_merge($request->getData(), ['custom_type' => NULL]))->save(); }
        return (new ProjectResource($project->load('type')))->additional(Helper::instance()->updateSuccess('project'));
    }

    public function destroy(Project $project)
    {
        Cloudinary::destroy($project->pdf_name);
        $project->delete();
        return response()->json(Helper::instance()->destroySuccess('project'));
    }

    public function report($date_start, $date_end, $sort_column, $sort_option) {
        $title = 'Report - No data';
        $description = 'No data';

        try {

        $projects = Project::with('type')
            ->whereBetween('created_at', [$date_start, $date_end])
            ->orderBy($sort_column, $sort_option)
            ->get();


        } catch(\Illuminate\Database\QueryException $ex){
            return view('errors.404Report', compact('title', 'description'));
        }

        $projectsData = null;

        $firstDayYear = date('Y-m-d', strtotime('first day of january this year'));
        $lastDateYear = date('Y-m-d', strtotime('first day of december this year'));
        $firstDayMonth = date('Y-m-d',strtotime('first day of this month'));
        $lastDayMonth = date('Y-m-d',strtotime('last day of this month'));

        if (App::environment('production')) {
            $projectsData =  DB::table('projects')
                ->selectRaw("count(case when created_at >='". $firstDayYear ."' AND created_at <='".$lastDateYear."' then 1 end) as this_year_count")
                ->selectRaw("count(case when created_at >='". $firstDayMonth ."' AND created_at <='".$lastDayMonth."' then 1 end) as this_month_count")
                ->selectRaw("count(case when DATE(created_at) = CURRENT_DATE then 1 end) as this_day_count")
                ->first();
        } else {
            $projectsData =  DB::table('projects')
                ->selectRaw("count(case when created_at >='". $firstDayYear ."' AND created_at <='".$lastDateYear."' then 1 end) as this_year_count")
                ->selectRaw("count(case when created_at >='". $firstDayMonth ."' AND created_at <='".$lastDayMonth."' then 1 end) as this_month_count")
                ->selectRaw("count(case when DATE(created_at) = CURDATE() then 1 end) as this_day_count")
                ->first();
        }

        $title = 'Project Publish Report';
        $modelName = 'Project';

        return view('admin.information.pdf.projectreport', compact('title', 'modelName', 'projects' ,'projectsData',
        'date_start', 'date_end', 'sort_column', 'sort_option'

    ));
    }
}
