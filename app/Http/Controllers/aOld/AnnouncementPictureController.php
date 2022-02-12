<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AnnouncementPictureRequest;
use App\Http\Resources\AnnouncementPictureResource;
use App\Models\AnnouncementPicture;
use Illuminate\Support\Facades\Storage;

class AnnouncementPictureController extends Controller
{
    public function store(AnnouncementPictureRequest $request)
    {
        $fileName = time().'_'.$request->picture->getClientOriginalName();
        $filePath = $request->file('picture')->storeAs('documents', $fileName, 'public');
        $announcement_picture = AnnouncementPicture::create(array_merge($request->getData(), ['picture_name' => $fileName,'file_path' => $filePath]));
        return (new AnnouncementPictureResource($announcement_picture))->additional(Helper::instance()->storeSuccess('announcement_picture'));
    }

    public function destroy(AnnouncementPicture $announcement_picture)
    {
        Storage::delete('public/documents/'. $announcement_picture->picture_name);
        $announcement_picture->delete();
        return response()->json(Helper::instance()->destroySuccess('announcement_picture'));
    }
}
