<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserRequirementRequest;
use App\Models\Requirement;
use App\Models\UserRequirement;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Storage;

class UserRequirementController extends Controller
{
    public function index()
    {
        $userRequirements = UserRequirement::where('user_id',auth('api')->user()->id)->orderBy('created_at', 'DESC')->get();
        return response()->json(["data" => $userRequirements], 200);
    }

    public function create()
    {
        $requirements = Requirement::get();
        $userRequirements = UserRequirement::where('user_id',auth('api')->user()->id)->orderBy('created_at', 'DESC')->get();
        $noRequirements = collect();

        foreach ($requirements as $requirement) {
            if ($userRequirements->count() > 0) {
                if (!$userRequirements->contains('requirement_id', $requirement->id)) {
                    if(!$noRequirements->contains('requirement_id', $requirement->id)) {
                        $noRequirements->push(['id' => $requirement->id, 'name' => $requirement->name]);
                    }
                }
            } else {
                $noRequirements->push(['id' => $requirement->id, 'name' => $requirement->name]);
            }
        }
        return response()->json(["data" => $noRequirements], 201);

    }

    public function store(UserRequirementRequest $request)
    {
        activity()->disableLogging();
        $result = cloudinary()->uploadFile('data:image/jpeg;base64,'.$request->picture, ['folder' => env('CLOUDINARY_PATH', 'dev-barangay')]);

        $userRequirement =  UserRequirement::create([
            'user_id' => auth('api')->user()->id,
            'requirement_id' => $request->requirement_id,
            'file_name' => $result->getPublicId(),
            'file_path' => $result->getPath(),
        ]);
        return response()->json(["data" => $userRequirement->load('requirement')], 200);
    }

    public function destroy(UserRequirement $userRequirement)
    {
        activity()->disableLogging();
        if ($userRequirement->user_id == auth('api')->user()->id) {
            $userRequirement = $userRequirement->load('requirement');
            $name = $userRequirement->requirement->name;
            Cloudinary::destroy($userRequirement->file_name);
            $userRequirement->delete();
            return response()->json(["message" => "Your requirement: ".$name." has been deleted in our data"], 200);
        } else {
            return response()->json(["message" => "You cannot delete the other users requirements."], 403);
        }
    }
}
