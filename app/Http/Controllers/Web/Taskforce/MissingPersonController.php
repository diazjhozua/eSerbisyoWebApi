<?php

namespace App\Http\Controllers\Web\Taskforce;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeStatusRequest;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\MissingPersonRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\MissingPersonResource;
use App\Jobs\ChangeStatusReportJob;
use App\Models\MissingPerson;
use Auth;
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

        $missing_persons = MissingPerson::withCount('comments')->orderBy('created_at','DESC')->get();

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
            $fileName = time().'_'.$request->picture->getClientOriginalName();
            $filePath = $request->file('picture')->storeAs('missing-pictures', $fileName, 'public');
            $missing_person = MissingPerson::create(array_merge($request->getData(), ['user_id' => Auth::id(),'status' => 'Pending', 'picture_name' => $fileName,'file_path' => $filePath]));
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
                Storage::delete('public/missing-pictures/'. $missing_person->picture_name);
                $fileName = time().'_'.$request->picture->getClientOriginalName();
                $filePath = $request->file('picture')->storeAs('missing-pictures', $fileName, 'public');
                $missing_person->fill(array_merge($request->getData(), ['status' => 'Pending', 'picture_name' => $fileName,'file_path' => $filePath]))->save();
            } else {   $missing_person->fill(array_merge($request->getData(), ['status' => 'Pending']))->save(); }
            return (new MissingPersonResource($missing_person->load('comments')->loadCount('comments')))->additional(Helper::instance()->updateSuccess('missing-person report'));
        }
    }

    public function destroy(MissingPerson $missing_person)
    {
        if(request()->ajax()) {
            Storage::delete('public/missing-pictures/'. $missing_person->picture_name);
            $missing_person->delete();
            return response()->json(Helper::instance()->destroySuccess('missing-person report'));
        }
    }

    public function changeStatus(ChangeStatusRequest $request, MissingPerson $missing_person)
    {
        if(request()->ajax()) {

            // if ($request->status == $missing_person->status) {
            //     return response()->json(Helper::instance()->sameStatusMessage($request->status, 'missing-person report'));
            // }

            $oldStatus = $missing_person->status;
            $missing_person->fill($request->validated())->save();

            $subject = 'Missing Person Report Change Status Notification';
            $reportName = 'missing person report';
            dispatch(new ChangeStatusReportJob($missing_person->email, $missing_person->id, $reportName, $missing_person->status, $missing_person->admin_message, $subject));

            return (new MissingPersonResource($missing_person))->additional(Helper::instance()->statusMessage($oldStatus, $missing_person->status, 'missing-person report'));
        }
    }
}
