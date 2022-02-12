<?php

namespace App\Http\Controllers\Api;

use App\Events\MissingPersonEvent;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MissingPersonRequest;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;

use App\Http\Resources\MissingPersonResource;
use App\Models\MissingPerson;
use Illuminate\Support\Facades\Storage;

class MissingPersonController extends Controller
{
    public function index()
    {
        // fetch all approved missing person
        $missing_persons = MissingPerson::withCount('comments')->where('status', 'Approved')->orderBy('created_at','DESC')->get();
        return MissingPersonResource::collection($missing_persons)->additional(['success' => true]);
    }

    public function authReports()
    {
        // fetch all authenticated missing person item
        $missing_persons = MissingPerson::withCount('comments')->where('contact_user_id', auth('api')->user()->id)->orderBy('created_at','DESC')->get();
        return MissingPersonResource::collection($missing_persons)->additional(['success' => true]);
    }

    public function getCommentList(MissingPerson $missingPerson) {
        $missingPerson = $missingPerson->load('comments');
        $comments = $missingPerson->comments;
        return (CommentResource::collection($comments));
    }

    public function comment(CommentRequest $request, MissingPerson $missingPerson) {
        activity()->disableLogging();
        $comment = $missingPerson->comments()->create(array_merge($request->validated(), ['user_id' => auth('api')->user()->id]));
        return (new CommentResource($comment))->additional(Helper::instance()->storeSuccess('comment'));
    }

    public function store(MissingPersonRequest $request)
    {
        activity()->disableLogging();
        $missingFileName = uniqid().time().'.jpg';
        $missingFilePath = 'missing-pictures/'.$missingFileName;
        Storage::disk('public')->put($missingFilePath, base64_decode($request->picture));

        $credentialFileName = uniqid().time().'.jpg';
        $credentialFilePath = 'credentials/'.$credentialFileName;
        Storage::disk('public')->put($credentialFilePath, base64_decode($request->credential));

        $missingPerson = MissingPerson::create(array_merge($request->getData(),
            [
            'user_id' => auth('api')->user()->id, 'contact_user_id' => auth('api')->user()->id, 'status' => 'Pending',
            'picture_name' => $missingFileName, 'file_path' => $missingFilePath,
            'credential_name' => $credentialFileName, 'credential_file_path' => $credentialFilePath
            ]
        ));

        $missingPerson->comments_count = 0;
        event(new MissingPersonEvent($missingPerson));
        return (new MissingPersonResource($missingPerson))->additional(Helper::instance()->storeSuccess('missing person report'));

    }

    // public function show(MissingPerson $missingPerson)
    // {
    //     return (new MissingPersonResource($missingPerson->load('comments')->loadCount('comments')))->additional(array_merge(['statuses' => $statuses],Helper::instance()->itemFound('missing-person report')));
    // }

    public function edit(MissingPerson $missingPerson)
    {
        // $reportTypes = [ (object)[ "id" => 1, "type" => "Missing"],(object) ["id" => 2,"type" => "Found"] ];
        // $heightUnits = [ (object)[ "id" => 1, "unit" => "feet(ft)"],(object) ["id" => 2, "unit" => "centimeter(cm)"] ];
        // $weightUnits = [ (object)[ "id" => 1, "unit" => "kilogram(kg)"],(object) ["id" => 2, "unit" => "kilogram(kg)"] ];

        if ($missingPerson->contact_user_id != auth('api')->user()->id) {
            return response()->json(["message" => "You can only edit your reports."], 403);
        }

        return (new MissingPersonResource($missingPerson->loadCount('comments')));
        // return (new MissingPersonResource($missingPerson))->additional(array_merge(['reportTypes' => $reportTypes, 'heightUnits' => $heightUnits,  'weightUnits' => $weightUnits], Helper::instance()->itemFound('missing-person report')));
    }

    public function update(MissingPersonRequest $request, MissingPerson $missingPerson)
    {

        if ($missingPerson->contact_user_id != auth('api')->user()->id) {
            return response()->json(["message" => "You can only update your reports."], 403);
        }

        $missingFileName = $missingPerson->picture_name;
        $missingFilePath = $missingPerson->file_path;
        $credentialFileName = $missingPerson->credential_name;
        $credentialFilePath = $missingPerson->credential_file_path;

        if ($request->picture != '') {
            if($missingPerson->picture_name != '') {
                Storage::delete('public/missing-pictures/'. $missingPerson->picture_name);
            }

            $missingFileName = uniqid().time().'.jpg';
            $missingFilePath = 'missing-pictures/'.$missingFileName;
            Storage::disk('public')->put($missingFilePath, base64_decode($request->picture));
        }

        if ($request->credential != '') {
            if($missingPerson->credential_name != '') {
                Storage::delete('public/credentials/'. $missingPerson->credential_name);
            }
            $credentialFileName = uniqid().time().'.jpg';
            $credentialFilePath = 'credentials/'.$credentialFileName;
            Storage::disk('public')->put($credentialFilePath, base64_decode($request->credential));
        }

        $missingPerson->fill(array_merge($request->getData(),             [
            'status' => 'Pending',
            'picture_name' => $missingFileName, 'file_path' => $missingFilePath,
            'credential_name' => $credentialFileName, 'credential_file_path' => $credentialFilePath,
            ]))->save();

        return (new MissingPersonResource($missingPerson->load('comments')->loadCount('comments')))->additional(Helper::instance()->updateSuccess('missing person report'));
    }

    public function destroy(MissingPerson $missingPerson)
    {
        if ($missingPerson->contact_user_id != auth('api')->user()->id) {
            return response()->json(["message" => "You can only delete your reports."], 403);
        }

        Storage::delete('public/missing-pictures/'. $missingPerson->picture_name);
        Storage::delete('public/credentials/'. $missingPerson->credential_name);
        $missingPerson->delete();
        return response()->json(Helper::instance()->destroySuccess('missing person report'));
    }

    // public function changeStatus(ChangeStatusRequest $request, MissingPerson $missing_person) {
    //     if ($request->status == $missing_person->status) {
    //         return response()->json(Helper::instance()->sameStatusMessage($request->status, 'missing-person report'));
    //     }
    //     $oldStatus = $missing_person->status;
    //     $missing_person->fill($request->validated())->save();
    //     return (new MissingPersonResource($missing_person))->additional(Helper::instance()->statusMessage($oldStatus, $missing_person->status, 'missing-person report'));
    // }

    //     public function create()
    // {
    //     $reportTypes = [ (object)[ "id" => 1, "type" => "Missing"],(object) ["id" => 2,"type" => "Found"] ];
    //     $heightUnits = [ (object)[ "id" => 1, "unit" => "feet(ft)"],(object) ["id" => 2, "unit" => "centimeter(cm)"] ];
    //     $weightUnits = [ (object)[ "id" => 1, "unit" => "kilogram(kg)"],(object) ["id" => 2, "unit" => "kilogram(kg)"] ];
    //     return response()->json(['reportTypes' => $reportTypes, 'heightUnits' => $heightUnits,  'weightUnits' => $weightUnits, 'success' => true]);
    // }


}
