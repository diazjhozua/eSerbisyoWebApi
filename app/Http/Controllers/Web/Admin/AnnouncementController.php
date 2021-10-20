<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AnnouncementRequest;
use App\Http\Resources\AnnouncementResource;
use App\Http\Resources\TypeResource;
use App\Models\Announcement;
use App\Models\AnnouncementPicture;
use App\Models\Type;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    public function index()
    {
        $firstDayYear = date('Y-m-d', strtotime('first day of january this year'));
        $lastDateYear = date('Y-m-d', strtotime('first day of december this year'));
        $firstDayMonth = date('Y-m-d',strtotime('first day of this month'));
        $lastDayMonth = date('Y-m-d',strtotime('last day of this month'));

        $announcementsData =  DB::table('announcements')
        ->selectRaw("count(case when created_at >='". $firstDayYear ."' AND created_at <='".$lastDateYear."' then 1 end) as this_year_count")
        ->selectRaw("count(case when created_at >='". $firstDayMonth ."' AND created_at <='".$lastDayMonth."' then 1 end) as this_month_count")
        ->selectRaw("count(case when DATE(created_at) = CURDATE() then 1 end) as this_day_count")
        ->first();

        $announcements = Announcement::with('type')->withCount('comments', 'likes', 'announcement_pictures')->orderBy('created_at', 'DESC')->get();
        return view('admin.announcements.index', compact('announcementsData', 'announcements'));
    }

    public function create()
    {
        $types = Type::where('model_type', 'Announcement')->get();
        return ['types' => TypeResource::collection($types), 'success' => true];
    }

    public function store(AnnouncementRequest $request)
    {
        $announcement = Announcement::create($request->getData());
        $announcement->comments_count = 0;
        $announcement->likes_count = 0;
        if (isset($request->picture_list)) {
            foreach ($request->picture_list as $file) {
                $fileName = time().'_'.$file->getClientOriginalName();
                $filePath =   $file->storeAs('announcements', $fileName, 'public');
                AnnouncementPicture::create(['announcement_id' => $announcement->id, 'picture_name' => $fileName,'file_path' => $filePath]);
            }
        }
        return (new AnnouncementResource($announcement->load('type')->loadCount('announcement_pictures')))->additional(Helper::instance()->storeSuccess('announcement'));
    }

    public function show(Announcement $announcement)
    {
        $announcement->load('likes', 'comments', 'type','announcement_pictures')->loadCount('likes', 'comments', 'announcement_pictures');
        return view('admin.announcements.show')->with('announcement', $announcement);
    }

    public function edit(Announcement $announcement)
    {
        $types = Type::where('model_type', 'Announcement')->get();
        return (new AnnouncementResource($announcement->load('type')))->additional(array_merge(['types' => TypeResource::collection($types)],Helper::instance()->itemFound('announcement')));
    }

    public function update(AnnouncementRequest $request, Announcement $announcement)
    {
        $announcement->fill($request->getData())->save();
        return (new AnnouncementResource($announcement->load('likes', 'comments', 'type','announcement_pictures')->loadCount('likes', 'comments')))->additional(Helper::instance()->updateSuccess('announcement'));
    }

    public function destroy(Announcement $announcement)
    {
        return DB::transaction(function() use ($announcement) {
            $announcement->load('announcement_pictures');
            foreach ($announcement->announcement_pictures as $picture) { Storage::delete('public/announcements/'. $picture->picture_name); }
            AnnouncementPicture::where('announcement_id', $announcement->id)->delete();
            $announcement->comments()->delete();
            $announcement->delete();
            return response()->json(Helper::instance()->destroySuccess('announcement'));
        });
    }
}
