<?php

namespace App\Http\Controllers\Api;

use App\Events\ComplaintEvent;
use App\Events\FeedbackEvent;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ApiComplaintRequest;
use App\Http\Requests\Api\ComplaintRequest;
use App\Http\Resources\ComplaintResource;
use App\Http\Resources\TypeResource;
use App\Models\Complainant;
use App\Models\Complaint;
use App\Models\Defendant;
use App\Models\Type;
use Carbon\Carbon;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ComplaintController extends Controller
{

    public function index() {
        $complaints = Complaint::with('type')->where('contact_user_id', auth('api')->user()->id)->orderBy('id','desc')->get();
        return ComplaintResource::collection($complaints);
    }

    public function create()
    {
        $types = Type::where('model_type', 'Complaint')->get();
        return ['types' => TypeResource::collection($types), 'success' => true] ;
    }

    public function store(ApiComplaintRequest $request)
    {
        activity()->disableLogging();
        return DB::transaction(function() use ($request) {
            $complaint = Complaint::create(array_merge($request->getData(), ['status' => 'Pending', 'user_id' => auth('api')->user()->id, 'contact_user_id' => auth('api')->user()->id]));
            $complainantCount = 0;
            $defendantCount = 0;

            foreach ($request->complainant_list as $key => $value) {
                $picture = cloudinary()->uploadFile('data:image/jpeg;base64,'.$value['signature'], ['folder' => env('CLOUDINARY_PATH', 'dev-barangay')]);

                Complainant::create(['complaint_id' => $complaint->id, 'name' => $value['name'], 'signature_picture' =>  $picture->getPublicId(),'file_path' => $picture->getPath()]);
                $complainantCount++;
            }

            foreach ($request->defendant_list as $key => $value) {
                Defendant::create(['complaint_id' => $complaint->id, 'name' => $value['name']]);
                $defendantCount++;
            }

            $complaint->complainants_count = $complainantCount;
            $complaint->defendants_count = $defendantCount;

            event(new ComplaintEvent($complaint->load('type')));
            return (new ComplaintResource($complaint->load('type')))->additional(Helper::instance()->storeSuccess('complaint'));
        });
    }
    public function show(Complaint $complaint)
    {
        activity()->disableLogging();
        if ($complaint->contact_user_id != auth('api')->user()->id) {
            return response()->json(["message" => "You can only view your submitted complaint."], 403);
        }

        return (new ComplaintResource($complaint->load('type', 'complainants', 'defendants')->loadCount('complainants', 'defendants')))->additional(Helper::instance()->itemFound('complaint'));
    }

    public function edit(Complaint $complaint)
    {
        $types = Type::where('model_type', 'Complaint')->get();
        return (new ComplaintResource($complaint->load('type', 'complainants', 'defendants')))->additional(array_merge(['types' => TypeResource::collection($types)],Helper::instance()->itemFound('complaint')));
    }

    public function update(ComplaintRequest $request, Complaint $complaint)
    {
        activity()->disableLogging();
        if ($complaint->contact_user_id != auth('api')->user()->id) {
            return response()->json(["message" => "You can only update your submitted complaint."], 403);
        }

        if ($complaint->status == 'Approved' || $complaint->status == 'Resolved') {
            return response()->json(["message" => "You can only update your complaint when the status is Pending or Denied"], 403);
        }

        $complaint->fill(array_merge($request->getData(), ['status' => 'Pending']))->save();
        return (new ComplaintResource($complaint->load('type')))->additional(Helper::instance()->updateSuccess('complaint'));
    }

    public function destroy(Complaint $complaint)
    {
        activity()->disableLogging();
        return DB::transaction(function() use ($complaint) {

            if ($complaint->contact_user_id != auth('api')->user()->id) {
                return response()->json(["message" => "You can only delete your submitted complaint."], 403);
            }

            if ($complaint->status == 'Approved' || $complaint->status == 'Resolved') {
                return response()->json(["message" => "You can only delete your complaint when the status is Pending or Denied"], 403);
            }

            $complaint->load('complainants');
            foreach ($complaint->complainants as $complainant) {
                Cloudinary::destroy($complainant->signature_picture);
            }
            Complainant::where('complaint_id', $complaint->id)->delete();
            Defendant::where('complaint_id', $complaint->id)->delete();
            $complaint->delete();

            return response()->json(Helper::instance()->destroySuccess('complaint'));
        });
    }

    // get short analytics about the overall overview.
    public function getAnalytics()
    {
        $complaintTypes = Type::withCount(['complaints' => function($query){
            $query->where('created_at', '>=', date('Y-m-d',strtotime('first day of this month')))
            ->where('created_at', '<=', date('Y-m-d',strtotime('last day of this month')));
        }])
        ->where('model_type', 'Complaint')->orderBy('complaints_count', 'DESC')->get();

        $trendingComplaints = Type::where('model_type', 'Complaint')->withCount('complaints')->orderBy('complaints_count', 'DESC')->limit(5)->get();

        $complaints = Complaint::select('id', 'created_at')
            ->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('m'); // grouping by years
        });

        $userAverageComplaint = [];
        $userComplaint = [];

        foreach ($complaints as $key => $value) {
            $yearList = [];
            $userComplaintCount = 0;

            foreach ($value as $userComplaintData) {
                $userComplaintCount ++;
                $year = Carbon::parse($userComplaintData->created_at)->format('Y');
                if (!in_array($year, $yearList)) {
                    array_push($yearList, $year);
                }
            }

            $userAverageComplaint[(int)$key] = round($userComplaintCount / count($yearList), 2);
        }

        for($i = 1; $i <= 12; $i++){
            if(!empty($userAverageComplaint[$i])){
                $userComplaint[$i] = $userAverageComplaint[$i];
            }else{
                $userComplaint[$i] = 0;
            }
        }
        return response()->json([
            'complaintTypes' => $complaintTypes,
            'userComplaint' => $userComplaint,
            'trendingComplaints' => $trendingComplaints,
        ], 200);
    }

}
