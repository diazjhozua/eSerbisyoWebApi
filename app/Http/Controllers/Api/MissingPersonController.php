<?php

namespace App\Http\Controllers\Api;

use App\Events\MissingPersonEvent;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeStatusRequest;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\MissingPersonRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\MissingPersonResource;
use App\Models\MissingPerson;
use Illuminate\Support\Facades\Storage;

class MissingPersonController extends Controller
{
    public function index()
    {
        // fetch all approved missing person
        $missing_persons = MissingPerson::withCount('comments')->orderBy('created_at','DESC')->get();
        return MissingPersonResource::collection($missing_persons)->additional(['success' => true]);
    }

    public function create()
    {
        $reportTypes = [ (object)[ "id" => 1, "type" => "Missing"],(object) ["id" => 2,"type" => "Found"] ];
        $heightUnits = [ (object)[ "id" => 1, "unit" => "feet(ft)"],(object) ["id" => 2, "unit" => "centimeter(cm)"] ];
        $weightUnits = [ (object)[ "id" => 1, "unit" => "kilogram(kg)"],(object) ["id" => 2, "unit" => "kilogram(kg)"] ];
        return response()->json(['reportTypes' => $reportTypes, 'heightUnits' => $heightUnits,  'weightUnits' => $weightUnits, 'success' => true]);
    }

    public function store(MissingPersonRequest $request)
    {
        $fileName = time().'_'.$request->picture->getClientOriginalName();
        $filePath = $request->file('picture')->storeAs('missing-pictures', $fileName, 'public');
        $missing_person = MissingPerson::create(array_merge($request->getData(), ['user_id' => 2,'status' => 'Pending', 'picture_name' => $fileName,'file_path' => $filePath]));
        event(new MissingPersonEvent($missing_person));

        $missing_person->comments_count = 0;
        return (new MissingPersonResource($missing_person))->additional(Helper::instance()->storeSuccess('missing-person report'));
    }

    public function show(MissingPerson $missingPerson)
    {
        $statuses = [ (object)[ "id" => 1, "name" => "Pending"], (object) ["id" => 2,"type" => "Denied"] ,
            (object) ["id" => 3,"type" => "Approved"], (object) ["id" => 4,"type" => "Resolved"] ];
        return (new MissingPersonResource($missingPerson->load('comments')->loadCount('comments')))->additional(array_merge(['statuses' => $statuses],Helper::instance()->itemFound('missing-person report')));
    }

    public function edit(MissingPerson $missingPerson)
    {
        $reportTypes = [ (object)[ "id" => 1, "type" => "Missing"],(object) ["id" => 2,"type" => "Found"] ];
        $heightUnits = [ (object)[ "id" => 1, "unit" => "feet(ft)"],(object) ["id" => 2, "unit" => "centimeter(cm)"] ];
        $weightUnits = [ (object)[ "id" => 1, "unit" => "kilogram(kg)"],(object) ["id" => 2, "unit" => "kilogram(kg)"] ];
        return (new MissingPersonResource($missingPerson))->additional(array_merge(['reportTypes' => $reportTypes, 'heightUnits' => $heightUnits,  'weightUnits' => $weightUnits], Helper::instance()->itemFound('missing-person report')));
    }

    public function update(MissingPersonRequest $request, MissingPerson $missingPerson)
    {
        if($request->hasFile('picture')) {
            Storage::delete('public/missing-pictures/'. $missingPerson->picture_name);
            $fileName = time().'_'.$request->picture->getClientOriginalName();
            $filePath = $request->file('picture')->storeAs('missing-pictures', $fileName, 'public');
            $missingPerson->fill(array_merge($request->getData(), ['status' => 'Pending', 'picture_name' => $fileName,'file_path' => $filePath]))->save();
        } else {   $missingPerson->fill(array_merge($request->getData(), ['status' => 'Pending']))->save(); }
        return (new MissingPersonResource($missingPerson->load('comments')->loadCount('comments')))->additional(Helper::instance()->updateSuccess('missing-person report'));
    }

    public function destroy(MissingPerson $missing_person)
    {
        Storage::delete('public/missing-pictures/'. $missing_person->picture_name);
        $missing_person->delete();
        return response()->json(Helper::instance()->destroySuccess('missing-person report'));
    }


    public function comment(CommentRequest $request, MissingPerson $missing_person) {
        $comment = $missing_person->comments()->create(array_merge($request->validated(), ['user_id' => 2]));
        return (new CommentResource($comment))->additional(Helper::instance()->storeSuccess('comment'));
    }

    // public function changeStatus(ChangeStatusRequest $request, MissingPerson $missing_person) {
    //     if ($request->status == $missing_person->status) {
    //         return response()->json(Helper::instance()->sameStatusMessage($request->status, 'missing-person report'));
    //     }
    //     $oldStatus = $missing_person->status;
    //     $missing_person->fill($request->validated())->save();
    //     return (new MissingPersonResource($missing_person))->additional(Helper::instance()->statusMessage($oldStatus, $missing_person->status, 'missing-person report'));
    // }

}
