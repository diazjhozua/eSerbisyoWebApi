<?php

namespace App\Http\Controllers\Web\Taskforce;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeStatusRequest;
use App\Http\Requests\ComplainantRequest;
use App\Http\Requests\ComplaintRequest;
use App\Http\Resources\ComplaintResource;
use App\Http\Resources\TypeResource;
use App\Jobs\ChangeStatusReportJob;
use App\Models\Complainant;
use App\Models\Complaint;
use App\Models\Defendant;
use App\Models\Type;
use DB;
use File;
use Helper;
use Illuminate\Http\Request;
use Storage;

class ComplaintController extends Controller
{
    public function index()
    {
        $complaintsData =  DB::table('complaints')
        ->selectRaw('count(*) as complaints_count')
        ->selectRaw("count(case when status = 'Pending' then 1 end) as pending_count")
        ->selectRaw("count(case when status = 'Approved' then 1 end) as approved_count")
        ->selectRaw("count(case when status = 'Denied' then 1 end) as denied_count")
        ->selectRaw("count(case when status = 'Resolved' then 1 end) as resolved_count")
        ->where('created_at', '>=', date('Y-m-d',strtotime('first day of this month')))
        ->where('created_at', '<=', date('Y-m-d',strtotime('last day of this month')))
        ->first();

        $complaints = Complaint::with('type')->withCount('complainants', 'defendants')->orderBy('created_at','DESC')->get();

        return view('admin.taskforce.complaints.index', compact('complaints', 'complaintsData'));
    }

    public function create()
    {
        $types = Type::where('model_type', 'Complaint')->get();
        return view('admin.taskforce.complaints.create', compact('types'));
    }


    public function store(ComplaintRequest $request)
    {
        return DB::transaction(function() use ($request) {
            $complaint = Complaint::create(array_merge($request->getData(), ['status' => 'Pending','user_id' => 2]));
            $complainantCount = 0;
            $defendantCount = 0;

            foreach ($request->complainant_list as $key => $value) {

                // $name = preg_replace('/\s+/', '', $request->title);

                // $fileName = time().'-signature'.'.jpg';

                $image_parts = explode(";base64,", $value['signature']);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];

                $image_base64 = base64_decode($image_parts[1]);
                $fileName = uniqid().time().'.'.$image_type;
                $filePath = 'signatures/'.$fileName;
                // save to storage/app/photos as the new $filename
                Storage::disk('public')->put($filePath, $image_base64);

                // $filePath =   $value['signature']->storeAs('signatures', $fileName, 'public');

                Complainant::create(['complaint_id' => $complaint->id, 'name' => $value['name'], 'signature_picture' => $fileName,'file_path' => $filePath]);
                $complainantCount++;
            }

            foreach ($request->defendant_list as $key => $value) {
                Defendant::create(['complaint_id' => $complaint->id, 'name' => $value['name']]);
                $defendantCount++;
            }

            $complaint->complainants_count = $complainantCount;
            $complaint->defendants_count = $defendantCount;

            return (new ComplaintResource($complaint->load('type')))->additional(Helper::instance()->storeSuccess('complaint'));
        });
    }

    public function show(Complaint $complaint)
    {
        $types = Type::where('model_type', 'Complaint')->get();
        return view('admin.taskforce.complaints.show')
        ->with('complaint', $complaint->load('type', 'complainants', 'defendants')->loadCount('complainants', 'defendants'))
        ->with('types', $types);
    }

    public function edit(Complaint $complaint)
    {
        if(request()->ajax()) {
            $types = Type::where('model_type', 'Complaint')->get();
            return (new ComplaintResource($complaint->load('type')))->additional(array_merge(['complaintTypes' => TypeResource::collection($types)],Helper::instance()->itemFound('complaint')));
        }
    }

    public function update(ComplaintRequest $request, Complaint $complaint)
    {
        if(request()->ajax()) {
            $complaint->fill($request->getData())->save();
            return (new ComplaintResource($complaint->load('type', 'user', 'contact')))->additional(Helper::instance()->updateSuccess('complaint'));
        }
    }

    public function destroy(Complaint $complaint)
    {
        if(request()->ajax()) {
            return DB::transaction(function() use ($complaint) {
                $complaint->load('complainants');
                foreach ($complaint->complainants as $complainant) { Storage::delete('public/signatures/'. $complainant->signature_picture); }
                Complainant::where('complaint_id', $complaint->id)->delete();
                Defendant::where('complaint_id', $complaint->id)->delete();
                $complaint->delete();

                return response()->json(Helper::instance()->destroySuccess('complaint'));
            });
        }
    }

    public function changeStatus(ChangeStatusRequest $request, Complaint $complaint) {
        if(request()->ajax()) {

            $oldStatus = $complaint->status;
            $complaint->fill($request->validated())->save();

            $subject = 'Complaint Change Status Notification';
            $reportName = 'complaint report';
            dispatch(new ChangeStatusReportJob($complaint->email, $complaint->id, $reportName, $complaint->status, $complaint->admin_message, $subject));

            return (new ComplaintResource($complaint->load('type')))->additional(Helper::instance()->statusMessage($oldStatus, $complaint->status, 'complaint'));
        }
    }

    public function report($date_start,  $date_end, $sort_column, $sort_option, $status_option) {

        try {
            $complaints = Complaint::with('type', 'defendants', 'complainants')
                ->whereBetween('created_at', [$date_start, $date_end])
                ->orderBy($sort_column, $sort_option)
                ->where(function($query) use ($status_option) {
                    if($status_option == 'all') {
                        return null;
                    } else {
                        return $query->where('status', '=', ucwords($status_option));
                    }
                })->get();
        } catch(\Illuminate\Database\QueryException $ex){}

        if ($complaints->isEmpty()) {
            $title = 'Report - No data';
            $description = 'No data';
            return view('errors.404Report', compact('title', 'description'));
        }

        $complaintsData = null;

        $complaintsData =  DB::table('complaints')
            ->selectRaw('count(*) as complaints_count')
            ->selectRaw("count(case when status = 'Pending' then 1 end) as pending_count")
            ->selectRaw("count(case when status = 'Denied' then 1 end) as denied_count")
            ->selectRaw("count(case when status = 'Approved' then 1 end) as approved_count")
            ->selectRaw("count(case when status = 'Resolved' then 1 end) as resolved_count")
            ->where('created_at', '>=', $date_start)
            ->where('created_at', '<=', $date_end)
            ->where(function($query) use ($status_option) {
                if($status_option == 'all') {
                    return null;
                } else {
                    return $query->where('status', '=', ucwords($status_option));
                }
            })->first();

        $title = 'User Submitted Complaints Reports';
        $modelName = 'Complaint';

        return view('admin.taskforce.pdf.complaint', compact('title', 'modelName', 'complaints', 'complaintsData',
            'date_start', 'date_end', 'sort_column', 'sort_option', 'status_option'
        ));
    }

}
