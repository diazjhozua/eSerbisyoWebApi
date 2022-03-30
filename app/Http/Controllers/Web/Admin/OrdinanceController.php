<?php

namespace App\Http\Controllers\Web\Admin;

use App;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrdinanceRequest;
use App\Http\Requests\Report\OrdinanceReportRequest;
use App\Http\Resources\OrdinanceResource;
use App\Http\Resources\TypeResource;
use App\Jobs\SendNotificationJob;
use App\Models\Ordinance;
use App\Models\Type;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade as PDF;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class OrdinanceController extends Controller
{
    public function index()
    {
        $firstDayYear = date('Y-m-d', strtotime('first day of january this year'));
        $lastDateYear = date('Y-m-d', strtotime('first day of december this year'));
        $firstDayMonth = date('Y-m-d',strtotime('first day of this month'));
        $lastDayMonth = date('Y-m-d',strtotime('last day of this month'));

        if (App::environment('production')) {
            $ordinancesData =  DB::table('ordinances')
                ->selectRaw("count(case when created_at >='". $firstDayYear ."' AND created_at <='".$lastDateYear."' then 1 end) as this_year_count")
                ->selectRaw("count(case when created_at >='". $firstDayMonth ."' AND created_at <='".$lastDayMonth."' then 1 end) as this_month_count")
                ->selectRaw("count(case when DATE(created_at) = CURRENT_DATE then 1 end) as this_day_count")
                ->first();
        } else {
            $ordinancesData =  DB::table('ordinances')
                ->selectRaw("count(case when created_at >='". $firstDayYear ."' AND created_at <='".$lastDateYear."' then 1 end) as this_year_count")
                ->selectRaw("count(case when created_at >='". $firstDayMonth ."' AND created_at <='".$lastDayMonth."' then 1 end) as this_month_count")
                ->selectRaw("count(case when DATE(created_at) = CURDATE() then 1 end) as this_day_count")
                ->first();
        }



        $ordinances = Ordinance::with('type')->orderBy('id','DESC')->get();
        return view('admin.information.ordinances.index', compact('ordinancesData', 'ordinances'));
    }

    public function create()
    {
        $types = Type::where('model_type', 'Ordinance')->get();
        return ['types' => TypeResource::collection($types), 'success' => true];
    }

    public function store(OrdinanceRequest $request)
    {
        $fileName = uniqid().'-'.time();
        $result = $request->file('pdf')->storeOnCloudinaryAs(env('CLOUDINARY_PATH', 'dev-barangay'), $fileName);
        $ordinance = Ordinance::create(array_merge($request->getData(), ['title' => strtoupper($request->title), 'pdf_name' => $result->getPublicId(),'file_path' => $result->getPath()]));

        dispatch(new SendNotificationJob(User::where('is_subscribed', 'Yes')->get(), "New ordinance uploaded",
            "New ordinance ".$ordinance->name." has been uploaded in the application.", $ordinance->id, "App\Models\Ordinance",
        ));

        return (new OrdinanceResource($ordinance->load('type')))->additional(Helper::instance()->storeSuccess('ordinance'));
    }

    public function edit(Ordinance $ordinance)
    {
        $types = Type::where('model_type', 'Ordinance')->get();
        return (new OrdinanceResource($ordinance->load('type')))->additional(array_merge(['types' => TypeResource::collection($types)],Helper::instance()->itemFound('ordinance')));
    }

    public function update(OrdinanceRequest $request, Ordinance $ordinance)
    {
        if($request->hasFile('pdf')) {
            Cloudinary::destroy($ordinance->pdf_name);
            $fileName = uniqid().'-'.time();
            $result = $request->file('pdf')->storeOnCloudinaryAs(env('CLOUDINARY_PATH', 'dev-barangay'), $fileName);

            $ordinance->fill(array_merge($request->getData(), ['title' => strtoupper($request->title), 'pdf_name' => $result->getPublicId(),'file_path' => $result->getPath()]))->save();
        } else { $ordinance->fill(array_merge($request->getData(), ['title' => strtoupper($request->title), 'custom_type' => NULL]))->save(); }
        return (new OrdinanceResource($ordinance->load('type')))->additional(Helper::instance()->updateSuccess('ordinance'));
    }

    public function destroy(Ordinance $ordinance)
    {
        Cloudinary::destroy($ordinance->pdf_name);
        $ordinance->delete();
        return response()->json(Helper::instance()->destroySuccess('ordinance'));
    }

    public function report($date_start, $date_end, $sort_column, $sort_option) {

        $title = 'Report - No data';
        $description = 'No data';



        try {


        $ordinances = Ordinance::with('type')
            ->whereBetween('created_at', [$date_start, $date_end])
            ->orderBy($sort_column, $sort_option)
            ->get();


        } catch(\Illuminate\Database\QueryException $ex){
            return view('errors.404Report', compact('title', 'description'));
        }



        if ($ordinances->isEmpty()) {
            return response()->json(['No data'], 404);
        }

        $ordinancesData = null;

        $firstDayYear = date('Y-m-d', strtotime('first day of january this year'));
        $lastDateYear = date('Y-m-d', strtotime('first day of december this year'));
        $firstDayMonth = date('Y-m-d',strtotime('first day of this month'));
        $lastDayMonth = date('Y-m-d',strtotime('last day of this month'));

        if (App::environment('production')) {
            $ordinancesData =  DB::table('ordinances')
                ->selectRaw("count(case when created_at >='". $firstDayYear ."' AND created_at <='".$lastDateYear."' then 1 end) as this_year_count")
                ->selectRaw("count(case when created_at >='". $firstDayMonth ."' AND created_at <='".$lastDayMonth."' then 1 end) as this_month_count")
                ->selectRaw("count(case when DATE(created_at) = CURRENT_DATE then 1 end) as this_day_count")
                ->first();
        } else {
            $ordinancesData =  DB::table('ordinances')
                ->selectRaw("count(case when created_at >='". $firstDayYear ."' AND created_at <='".$lastDateYear."' then 1 end) as this_year_count")
                ->selectRaw("count(case when created_at >='". $firstDayMonth ."' AND created_at <='".$lastDayMonth."' then 1 end) as this_month_count")
                ->selectRaw("count(case when DATE(created_at) = CURDATE() then 1 end) as this_day_count")
                ->first();
        }

        $title = 'Ordinance Publish Report';
        $modelName = 'Ordinance';


        return view('admin.information.pdf.ordinancereport', compact('title', 'modelName', 'ordinances' ,'ordinancesData',
        'date_start', 'date_end', 'sort_column', 'sort_option'

    ));
        // $pdf = PDF::loadView('admin.information.reports.ordinance', compact('ordinances', 'request'))->setOptions(['defaultFont' => 'sans-serif'])->setPaper('a4', 'landscape');
        // return $pdf->stream();
    }
}
