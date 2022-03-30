<?php

namespace App\Http\Controllers\Web\Taskforce;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeStatusRequest;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\MissingPersonRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\MissingPersonResource;
use App\Jobs\ChangeStatusReportJob;
use App\Jobs\SendSingleNotificationJob;
use App\Models\MissingPerson;
use Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use DB;
use Helper;
use Illuminate\Support\Facades\Storage;

class MissingPersonController extends Controller
{
    public function index()
    {
        $missingPersonsData =  DB::table('missing_persons')
        ->selectRaw('count(*) as missing_persons_count')
        ->selectRaw("count(case when status = 'Pending' then 1 end) as pending_count")
        ->selectRaw("count(case when status = 'Approved' then 1 end) as approved_count")
        ->selectRaw("count(case when status = 'Denied' then 1 end) as denied_count")
        ->selectRaw("count(case when status = 'Resolved' then 1 end) as resolved_count")
        ->where('created_at', '>=', date('Y-m-d',strtotime('first day of this month')))
        ->where('created_at', '<=', date('Y-m-d',strtotime('last day of this month')))
        ->first();

        $missing_persons = MissingPerson::withCount('comments')->orderBy('id','DESC')->get();

        return view('admin.taskforce.missing-persons.index', compact('missing_persons', 'missingPersonsData'));
    }

    public function create()
    {
        if(request()->ajax()) {
            $reportTypes = [ (object)[ "id" => 1, "type" => "Missing"],(object) ["id" => 2,"type" => "Found"] ];
            $heightUnits = [ (object)[ "id" => 1, "unit" => "feet(ft)"],(object) ["id" => 2, "unit" => "centimeter(cm)"] ];
            $weightUnits = [ (object)[ "id" => 1, "unit" => "kilogram(kg)"],(object) ["id" => 2, "unit" => "pound(lbs)"] ];
            return response()->json(['reportTypes' => $reportTypes, 'heightUnits' => $heightUnits,  'weightUnits' => $weightUnits, 'success' => true]);
        }
    }

    public function store(MissingPersonRequest $request)
    {
        if(request()->ajax()) {
            $fileName = uniqid().'-'.time();
            $result = $request->file('picture')->storeOnCloudinaryAs(env('CLOUDINARY_PATH', 'dev-barangay'), $fileName);
            $missing_person = MissingPerson::create(array_merge($request->getData(), ['user_id' => Auth::id(), 'status' => 'Approved', 'picture_name' => $result->getPublicId(), 'file_path' => $result->getPath()]));
            return (new MissingPersonResource($missing_person))->additional(Helper::instance()->storeSuccess('missing-person report'));
        }
    }

    public function show(MissingPerson $missing_person)
    {
        $missing_person->load('comments')->loadCount('comments');
        return view('admin.taskforce.missing-persons.show', compact('missing_person'));
    }

    public function edit(MissingPerson $missing_person)
    {
        if(request()->ajax()) {
            $reportTypes = [ (object)[ "id" => 1, "type" => "Missing"],(object) ["id" => 2,"type" => "Found"] ];
            $heightUnits = [ (object)[ "id" => 1, "unit" => "feet(ft)"],(object) ["id" => 2, "unit" => "centimeter(cm)"] ];
            $weightUnits = [ (object)[ "id" => 1, "unit" => "kilogram(kg)"],(object) ["id" => 2, "unit" => "pound(lbs)"] ];
            return (new MissingPersonResource($missing_person))->additional(array_merge(['reportTypes' => $reportTypes, 'heightUnits' => $heightUnits,  'weightUnits' => $weightUnits], Helper::instance()->itemFound('missing-person report')));
        }
    }

    public function update(MissingPersonRequest $request, MissingPerson $missing_person)
    {
        if(request()->ajax()) {
            if($request->hasFile('picture')) {
                Cloudinary::destroy($missing_person->picture_name);
                $fileName = uniqid().'-'.time();
                $result = $request->file('picture')->storeOnCloudinaryAs(env('CLOUDINARY_PATH', 'dev-barangay'), $fileName);
                $missing_person->fill(array_merge($request->getData(), ['picture_name' => $result->getPublicId(), 'file_path' => $result->getPath()]))->save();
            } else {   $missing_person->fill(array_merge($request->getData()))->save(); }
            return (new MissingPersonResource($missing_person->load('comments', 'contact')->loadCount('comments')))->additional(Helper::instance()->updateSuccess('missing-person report'));
        }
    }

    public function destroy(MissingPerson $missing_person)
    {
        if(request()->ajax()) {
            Cloudinary::destroy($missing_person->picture_name);
            if ($missing_person->credential_name != Null) {
                Cloudinary::destroy($missing_person->credential_name);
            }
            $missing_person->delete();
            return response()->json(Helper::instance()->destroySuccess('missing-person report'));
        }
    }

    public function changeStatus(ChangeStatusRequest $request, MissingPerson $missing_person)
    {
        if(request()->ajax()) {

            $oldStatus = $missing_person->status;
            $missing_person->fill($request->validated())->save();

            $subject = 'Missing Person Report Change Status Notification';
            $reportName = 'missing person report';

            dispatch(
                new SendSingleNotificationJob(
                    $missing_person->contact->device_id, $missing_person->contact->id, "Missing Person Report Change Status Notification",
                    "Your submitted missing person #".$missing_person->id." status has been change by the administrator.", $missing_person->id,  "App\Models\MissingPerson"
            ));

            dispatch(new ChangeStatusReportJob($missing_person->email, $missing_person->id, $reportName, $missing_person->status, $missing_person->admin_message, $subject, $missing_person->phone_no));
            return (new MissingPersonResource($missing_person))->additional(Helper::instance()->statusMessage($oldStatus, $missing_person->status, 'missing-person report'));
        }
    }

    public function report($date_start,  $date_end, $sort_column, $sort_option, $report_option, $status_option) {

        try {
            $missingPersons = MissingPerson::whereBetween('created_at', [$date_start, $date_end])
                ->orderBy($sort_column, $sort_option)
                ->where(function($query) use ($report_option, $status_option) {
                    if($report_option == 'all' && $status_option == 'all') {
                        return null;
                    } elseif ($report_option == 'all' && $status_option != 'all') {
                        return $query->where('status', '=', ucwords($status_option));
                    } elseif ($report_option != 'all' && $status_option == 'all') {
                        return $query->where('report_type', '=', ucwords($report_option));
                    } else {
                        return $query->where('status', '=', ucwords($status_option))
                        ->where('report_type', '=', ucwords($report_option));
                    }
                })->get();
        } catch(\Illuminate\Database\QueryException $ex){}

        if ($missingPersons->isEmpty()) {
            $title = 'Report - No data';
            $description = 'No data';
            return view('errors.404Report', compact('title', 'description'));
        }

        $reportsData = null;

        $reportsData =  DB::table('missing_persons')
            ->selectRaw('count(*) as missing_persons_count')
            ->selectRaw("count(case when status = 'Pending' then 1 end) as pending_count")
            ->selectRaw("count(case when status = 'Resolved' then 1 end) as resolved_count")
            ->selectRaw("count(case when status = 'Denied' then 1 end) as denied_count")
            ->selectRaw("count(case when status = 'Approved' then 1 end) as approved_count")
            ->where('created_at', '>=', $date_start)
            ->where('created_at', '<=', $date_end)
            ->where(function($query) use ($report_option, $status_option) {
                if($report_option == 'all' && $status_option == 'all') {
                    return null;
                } elseif ($report_option == 'all' && $status_option != 'all') {
                    return $query->where('status', '=', ucwords($status_option));
                } elseif ($report_option != 'all' && $status_option == 'all') {
                    return $query->where('report_type', '=', ucwords($report_option));
                } else {
                    return $query->where('status', '=', ucwords($status_option))
                    ->where('report_type', '=', ucwords($report_option));
                }
            })->first();

        $title = 'Missing Person Reports';
        $modelName = 'Missing Person';

        return view('admin.taskforce.pdf.missingPerson', compact('title', 'modelName', 'missingPersons', 'reportsData',
            'date_start', 'date_end', 'sort_column', 'sort_option', 'report_option', 'status_option'
        ));
    }
}
