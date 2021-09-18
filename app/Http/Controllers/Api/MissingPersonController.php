<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeStatusRequest;
use App\Http\Requests\MissingPersonRequest;
use App\Http\Resources\MissingPersonResource;
use App\Models\MissingPerson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MissingPersonController extends Controller
{
    public function index()
    {
        $missing_persons = MissingPerson::orderBy('created_at','DESC')->get();
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
        return (new MissingPersonResource($missing_person))->additional(Helper::instance()->storeSuccess('missing-person report'));
    }

    public function show(MissingPerson $missing_person)
    {
        $statuses = [ (object)[ "id" => 1, "name" => "Pending"], (object) ["id" => 2,"type" => "Denied"] ,
            (object) ["id" => 3,"type" => "Approved"], (object) ["id" => 4,"type" => "Resolved"] ];
        return (new MissingPersonResource($missing_person))->additional(array_merge(['statuses' => $statuses],Helper::instance()->itemFound('missing-person report')));
    }

    public function edit(MissingPerson $missing_person)
    {
        $reportTypes = [ (object)[ "id" => 1, "type" => "Missing"],(object) ["id" => 2,"type" => "Found"] ];
        $heightUnits = [ (object)[ "id" => 1, "unit" => "feet(ft)"],(object) ["id" => 2, "unit" => "centimeter(cm)"] ];
        $weightUnits = [ (object)[ "id" => 1, "unit" => "kilogram(kg)"],(object) ["id" => 2, "unit" => "kilogram(kg)"] ];
        return (new MissingPersonResource($missing_person))->additional(array_merge(['reportTypes' => $reportTypes, 'heightUnits' => $heightUnits,  'weightUnits' => $weightUnits], Helper::instance()->itemFound('missing-person report')));
    }

    public function update(MissingPersonRequest $request, MissingPerson $missing_person)
    {
        if($request->hasFile('picture')) {
            Storage::delete('public/missing-pictures/'. $missing_person->picture_name);
            $fileName = time().'_'.$request->picture->getClientOriginalName();
            $filePath = $request->file('picture')->storeAs('missing-pictures', $fileName, 'public');
            $missing_person->fill(array_merge($request->getData(), ['status' => 'Pending', 'picture_name' => $fileName,'file_path' => $filePath]))->save();
        } else {   $missing_person->fill(array_merge($request->getData(), ['status' => 'Pending']))->save(); }
        return (new MissingPersonResource($missing_person))->additional(Helper::instance()->updateSuccess('missing-person report'));
    }

    public function destroy(MissingPerson $missing_person)
    {
        Storage::delete('public/missing-pictures/'. $missing_person->picture_name);
        $missing_person->delete();
        return response()->json(Helper::instance()->destroySuccess('missing-person report'));
    }

    public function changeStatus(ChangeStatusRequest $request, MissingPerson $missing_person) {
        if ($request->status == $missing_person->status) {
            return response()->json(Helper::instance()->sameStatusMessage($request->status, 'missing-person report'));
        }
        $oldStatus = $missing_person->status;
        $missing_person->fill($request->validated())->save();
        return (new MissingPersonResource($missing_person))->additional(Helper::instance()->statusMessage($oldStatus, $missing_person->status, 'missing-person report'));
    }

}
