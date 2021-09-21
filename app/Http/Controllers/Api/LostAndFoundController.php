<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeStatusRequest;
use App\Http\Requests\LostAndFoundRequest;
use App\Http\Resources\LostAndFoundResource;
use App\Models\LostAndFound;
use Illuminate\Support\Facades\Storage;

class LostAndFoundController extends Controller
{

    public function index()
    {
        $lost_and_founds = LostAndFound::orderBy('created_at','DESC')->get();
        return LostAndFoundResource::collection($lost_and_founds)->additional(['success' => true]);
    }

    public function create()
    {
        $reportTypes = [ (object)[ "id" => 1, "type" => "Missing"],(object) ["id" => 2,"type" => "Found"] ];
        return response()->json(['reportTypes' => $reportTypes, 'success' => true]);
    }

    public function store(LostAndFoundRequest $request)
    {
        $fileName = time().'_'.$request->picture->getClientOriginalName();
        $filePath = $request->file('picture')->storeAs('missing-pictures', $fileName, 'public');
        $lost_and_found = LostAndFound::create(array_merge($request->getData(), ['user_id' => 2, 'status' => 'Pending', 'picture_name' => $fileName,'file_path' => $filePath]));
        return (new LostAndFoundResource($lost_and_found))->additional(Helper::instance()->storeSuccess('lost-and-found report'));
    }

    public function show(LostAndFound $lost_and_found)
    {
        $statuses = [ (object)[ "id" => 1, "name" => "Pending"], (object) ["id" => 2,"type" => "Denied"] ,
        (object) ["id" => 3,"type" => "Approved"], (object) ["id" => 4,"type" => "Resolved"] ];
        return (new LostAndFoundResource($lost_and_found))->additional(array_merge(['statuses' => $statuses],Helper::instance()->itemFound('lost-and-found report')));
    }

    public function edit(LostAndFound $lost_and_found)
    {
        $reportTypes = [ (object)[ "id" => 1, "type" => "Missing"],(object) ["id" => 2,"type" => "Found"] ];
        return (new LostAndFoundResource($lost_and_found))->additional(array_merge(['reportTypes' => $reportTypes], Helper::instance()->itemFound('lost-and-found report')));
    }

    public function update(LostAndFoundRequest $request, LostAndFound $lost_and_found)
    {
        if($request->hasFile('picture')) {
            Storage::delete('public/missing-pictures/'. $lost_and_found->picture_name);
            $fileName = time().'_'.$request->picture->getClientOriginalName();
            $filePath = $request->file('picture')->storeAs('missing-pictures', $fileName, 'public');
            $lost_and_found->fill(array_merge($request->getData(), ['status' => 'Pending', 'picture_name' => $fileName,'file_path' => $filePath]))->save();
        } else {   $lost_and_found->fill(array_merge($request->getData(), ['status' => 'Pending']))->save(); }
        return (new LostAndFoundResource($lost_and_found))->additional(Helper::instance()->updateSuccess('lost-and-found report'));
    }

    public function destroy(LostAndFound $lost_and_found)
    {
        Storage::delete('public/missing-pictures/'. $lost_and_found->picture_name);
        $lost_and_found->delete();
        return response()->json(Helper::instance()->destroySuccess('lost-and-found report'));
    }

    public function changeStatus(ChangeStatusRequest $request, LostAndFound $lost_and_found) {
        if ($request->status == $lost_and_found->status) {
            return response()->json(Helper::instance()->sameStatusMessage($request->status, 'lost-and-found report'));
        }
        $oldStatus = $lost_and_found->status;
        $lost_and_found->fill($request->validated())->save();
        return (new LostAndFoundResource($lost_and_found))->additional(Helper::instance()->statusMessage($oldStatus, $lost_and_found->status, 'lost-and-found report'));
    }
}
