<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ComplaintRequest;
use App\Http\Resources\ComplaintResource;
use App\Http\Resources\TypeResource;
use App\Models\Complainant;
use App\Models\Complaint;
use App\Models\Defendant;
use App\Models\Type;
use Illuminate\Support\Facades\DB;

class ComplaintController extends Controller
{
    public function index()
    {
        $complaints = Complaint::with('type')->withCount('complainants', 'defendants')->orderBy('created_at', 'DESC')->get();
        return ComplaintResource::collection($complaints)->additional(['success' => true]);
    }

    public function create()
    {
        $types = Type::where('model_type', 'Complaint')->get();
        return ['types' => TypeResource::collection($types), 'success' => true] ;
    }

    public function store(ComplaintRequest $request)
    {
        DB::transaction(function() use ($request) {
            dd($request->getComplaint());
            $complaint = Complaint::create(array_merge($request->getComplaint(), ['user_id', 2]));

            $complainantCount = 0;
            $defendantCount = 0;
            foreach ($request->complainant_list as $key => $value) {
                $fileName = time().'_'.$value['signature']->getClientOriginalName();
                $filePath =   $value['signature']->storeAs('signatures', $fileName, 'public');
                Complainant::create(['complainant_id' => $complaint->id, 'name' => $value['name'], 'signature_picture' => $fileName,'file_path' => $filePath]);
                $complainantCount++;
            }
            foreach ($request->defendant_list as $key => $value) {
                Defendant::create(['complainant_id' => $complaint->id, 'name' => $value['name']]);
                $defendantCount++;
            }
            $complaint->complainants_count = $complainantCount;
            $complaint->defendants_count = $defendantCount;
            return (new ComplaintResource($complaint->load('type')))->additional(Helper::instance()->storeSuccess('document'));
        });

        return response()->json(['success' => false, 'messsage' => 'Sorry your request cannot be process']);
    }
    public function show(Complaint $complaint)
    {
        //
    }

    public function edit(Complaint $complaint)
    {
        //
    }

    public function update(ComplaintRequest $request, Complaint $complaint)
    {
        //
    }

    public function destroy(Complaint $complaint)
    {
        //
    }
}
