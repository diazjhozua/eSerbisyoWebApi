<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AnnouncementPictureRequest;
use App\Http\Resources\AnnouncementPictureResource;
use App\Models\AnnouncementPicture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnouncementPictureController extends Controller
{

    public function store(AnnouncementPictureRequest $request)
    {
        $fileName = time().'_'.$request->picture->getClientOriginalName();
        $filePath = $request->file('picture')->storeAs('announcements', $fileName, 'public');
        $announcement_picture = AnnouncementPicture::create(array_merge($request->getData(), ['picture_name' => $fileName,'file_path' => $filePath]));
        return (new AnnouncementPictureResource($announcement_picture))->additional(Helper::instance()->storeSuccess('announcement_picture'));
    }

    public function edit(AnnouncementPicture $announcement_picture)
    {
        return (new AnnouncementPictureResource($announcement_picture))->additional(Helper::instance()->itemFound('announcement_picture'));
    }

    public function update(AnnouncementPictureRequest $request, AnnouncementPicture $announcement_picture)
    {
        Storage::delete('public/announcements/'. $announcement_picture->picture_name);
        $fileName = time().'_'.$request->picture->getClientOriginalName();
        $filePath = $request->file('picture')->storeAs('announcements', $fileName, 'public');
        $announcement_picture->fill(['picture_name' => $fileName,'file_path' => $filePath])->save();
        return (new AnnouncementPictureResource($announcement_picture))->additional(Helper::instance()->updateSuccess('announcement_picture'));
    }

    public function destroy(AnnouncementPicture $announcement_picture)
    {
        Storage::delete('public/announcements/'. $announcement_picture->picture_name);
        $announcement_picture->delete();
        return response()->json(Helper::instance()->destroySuccess('announcement_picture'));
    }
}
