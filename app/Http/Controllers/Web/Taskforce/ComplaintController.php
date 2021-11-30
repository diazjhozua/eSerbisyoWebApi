<?php

namespace App\Http\Controllers\Web\Taskforce;

use App\Http\Controllers\Controller;
use App\Http\Requests\ComplainantRequest;
use App\Http\Requests\ComplaintRequest;
use App\Http\Resources\ComplaintResource;
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
                $filePath = 'signatures/'+$fileName;
                // save to storage/app/photos as the new $filename
                Storage::disk('local')->put($filePath, $image_base64);

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
        //
    }

    public function edit(Complaint $complaint)
    {
        //
    }

    public function update(Request $request, Complaint $complaint)
    {
        //
    }

    public function destroy(Complaint $complaint)
    {
        //
    }
}
