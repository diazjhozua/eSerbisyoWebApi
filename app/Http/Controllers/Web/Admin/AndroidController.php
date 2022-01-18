<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AndroidRequest;
use App\Http\Resources\AndroidResource;
use App\Models\Android;
use Helper;
use Storage;

class AndroidController extends Controller
{

    public function index()
    {
        $androids = Android::orderBy('created_at','DESC')->get();
        return view('admin.information.androids.index', compact('androids'));
    }

    public function store(AndroidRequest $request)
    {
        $fileName = time().'_'.$request->apk->getClientOriginalName();
        $filePath = $request->file('apk')->storeAs('androids', $fileName, 'public');
        $android = Android::create(array_merge($request->getData(), ['file_name' => $fileName,'file_path' => $filePath]));
        return (new AndroidResource($android))->additional(Helper::instance()->storeSuccess('android version'));
    }

    public function edit(Android $android)
    {
        return (new AndroidResource($android))->additional(Helper::instance()->itemFound('android version'));
    }

    public function update(AndroidRequest $request, Android $android)
    {
        if($request->hasFile('apk')) {
            Storage::delete('public/androids/'. $android->file_name);
            $fileName = time().'_'.$request->apk->getClientOriginalName();
            $filePath = $request->file('apk')->storeAs('androids', $fileName, 'public');
            $android->fill(array_merge($request->getData(), ['file_name' => $fileName,'file_path' => $filePath]))->save();
        } else { $android->fill($request->getData())->save(); }
        return (new AndroidResource($android))->additional(Helper::instance()->updateSuccess('android version'));
    }

    public function destroy(Android $android)
    {
        Storage::delete('public/androids/'. $android->file_name);
        $android->delete();
        return response()->json(Helper::instance()->destroySuccess('android version'));
    }
}
