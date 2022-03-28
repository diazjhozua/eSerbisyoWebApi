<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AndroidRequest;
use App\Http\Resources\AndroidResource;
use App\Jobs\SendNotificationJob;
use App\Models\Android;
use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Helper;

class AndroidController extends Controller
{

    public function index()
    {
        $androids = Android::orderBy('created_at','DESC')->get();
        return view('admin.information.androids.index', compact('androids'));
    }

    public function store(AndroidRequest $request)
    {
        $android = Android::create($request->getData());

        dispatch(new SendNotificationJob(User::where('is_subscribed', 'Yes')->get(), "New application release",
            "New application ".$android->version." has been uploaded. Please re-download the application", $android->id, "App\Models\Android",
        ));

        return (new AndroidResource($android))->additional(Helper::instance()->storeSuccess('android version'));


    }

    public function edit(Android $android)
    {
        return (new AndroidResource($android))->additional(Helper::instance()->itemFound('android version'));
    }

    public function update(AndroidRequest $request, Android $android)
    {
        $android->fill($request->getData())->save();
        return (new AndroidResource($android))->additional(Helper::instance()->updateSuccess('android version'));
    }

    public function destroy(Android $android)
    {
        // Cloudinary::destroy($android->file_name);
        $android->delete();
        return response()->json(Helper::instance()->destroySuccess('android version'));
    }
}
