<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AnnouncementPictureRequest;
use App\Http\Resources\AnnouncementPictureResource;
use App\Models\AnnouncementPicture;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnouncementPictureController extends Controller
{
    public function store(AnnouncementPictureRequest $request)
    {
        $fileName = uniqid().'-'.time();
        $result = $request->file('picture')->storeOnCloudinaryAs(env('CLOUDINARY_PATH', 'dev-barangay'), $fileName);
        $announcement_picture = AnnouncementPicture::create(array_merge($request->getData(), ['picture_name' => $result->getPublicId(),'file_path' => $result->getPath()]));
        return (new AnnouncementPictureResource($announcement_picture))->additional(Helper::instance()->storeSuccess('announcement_picture'));
    }

    public function edit(AnnouncementPicture $announcement_picture)
    {
        return (new AnnouncementPictureResource($announcement_picture))->additional(Helper::instance()->itemFound('announcement_picture'));
    }

    public function update(AnnouncementPictureRequest $request, AnnouncementPicture $announcement_picture)
    {
        Cloudinary::destroy($announcement_picture->picture_name);
        $fileName = uniqid().'-'.time();
        $result = $request->file('picture')->storeOnCloudinaryAs(env('CLOUDINARY_PATH', 'dev-barangay'), $fileName);
        $announcement_picture->fill(['picture_name' => $result->getPublicId(), 'file_path' => $result->getPath()])->save();
        return (new AnnouncementPictureResource($announcement_picture))->additional(Helper::instance()->updateSuccess('announcement_picture'));
    }

    public function destroy(AnnouncementPicture $announcement_picture)
    {
        Cloudinary::destroy($announcement_picture->picture_name);
        $announcement_picture->delete();
        return response()->json(Helper::instance()->destroySuccess('announcement_picture'));
    }
}
