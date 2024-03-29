<?php

namespace App\Http\Controllers\Web\Admin;

use App;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AnnouncementRequest;
use App\Http\Requests\Report\AnnouncementReportRequest;
use App\Http\Resources\AnnouncementResource;
use App\Http\Resources\TypeResource;
use App\Jobs\SendNotificationJob;
use App\Models\Announcement;
use App\Models\AnnouncementPicture;
use App\Models\Type;
use App\Models\User;
use Barryvdh\DomPDF\Facade as PDF;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Log;

class AnnouncementController extends Controller
{
    public function index()
    {
        $firstDayYear = date('Y-m-d', strtotime('first day of january this year'));
        $lastDateYear = date('Y-m-d', strtotime('first day of december this year'));
        $firstDayMonth = date('Y-m-d',strtotime('first day of this month'));
        $lastDayMonth = date('Y-m-d',strtotime('last day of this month'));

        if (App::environment('production')) {
            $announcementsData =  DB::table('announcements')
                ->selectRaw("count(case when created_at >='". $firstDayYear ."' AND created_at <='".$lastDateYear."' then 1 end) as this_year_count")
                ->selectRaw("count(case when created_at >='". $firstDayMonth ."' AND created_at <='".$lastDayMonth."' then 1 end) as this_month_count")
                ->selectRaw("count(case when DATE(created_at) = CURRENT_DATE then 1 end) as this_day_count")
                ->first();
        } else {
            $announcementsData =  DB::table('announcements')
                ->selectRaw("count(case when created_at >='". $firstDayYear ."' AND created_at <='".$lastDateYear."' then 1 end) as this_year_count")
                ->selectRaw("count(case when created_at >='". $firstDayMonth ."' AND created_at <='".$lastDayMonth."' then 1 end) as this_month_count")
                ->selectRaw("count(case when DATE(created_at) = CURDATE() then 1 end) as this_day_count")
                ->first();
        }

        $announcements = Announcement::with('type')->withCount('comments', 'likes', 'announcement_pictures')->orderBy('id', 'DESC')->get();
        return view('admin.information.announcements.index', compact('announcementsData', 'announcements'));
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
                $fileName = uniqid().'-'.time();
                $result = $file->storeOnCloudinaryAs(env('CLOUDINARY_PATH', 'dev-barangay'), $fileName);
                AnnouncementPicture::create(['announcement_id' => $announcement->id, 'picture_name' => $result->getPublicId(),'file_path' => $result->getPath()]);
            }
        }

        dispatch(
            new SendNotificationJob(
                User::where('is_subscribed', 'Yes')->get(), "New announcement posted",
                "New announcement has been posted by the barangay officials about ".$announcement->type->name, $announcement->id, "App\Models\Announcement",
        ));

        return (new AnnouncementResource($announcement->load('type')->loadCount('announcement_pictures')))->additional(Helper::instance()->storeSuccess('announcement'));
    }

    public function show(Announcement $announcement)
    {
        $announcement->load('likes', 'comments', 'type','announcement_pictures')->loadCount('likes', 'comments', 'announcement_pictures');
        return view('admin.information.announcements.show')->with('announcement', $announcement);
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
            foreach ($announcement->announcement_pictures as $picture) {
                Cloudinary::destroy($picture->picture_name);
                // Storage::delete('public/announcements/'. $picture->picture_name);
            }
            AnnouncementPicture::where('announcement_id', $announcement->id)->delete();
            $announcement->comments()->delete();
            $announcement->delete();
            return response()->json(Helper::instance()->destroySuccess('announcement'));
        });
    }

    public function report($date_start, $date_end, $sort_column, $sort_option) {

        $title = 'Report - No data';
        $description = 'No data';

        try {


        $announcements = Announcement::with('type')
            ->withCount('comments', 'likes', 'announcement_pictures')
            ->whereBetween('created_at', [$date_start, $date_end])
            ->orderBy($sort_column, $sort_option)
            ->get();

        } catch(\Illuminate\Database\QueryException $ex){
            return view('errors.404Report', compact('title', 'description'));
        }

        if ($announcements->isEmpty()) {
            return response()->json(['No data'], 404);
        }

        $announcementsData = null;


        $firstDayYear = date('Y-m-d', strtotime('first day of january this year'));
        $lastDateYear = date('Y-m-d', strtotime('first day of december this year'));
        $firstDayMonth = date('Y-m-d',strtotime('first day of this month'));
        $lastDayMonth = date('Y-m-d',strtotime('last day of this month'));

        if (App::environment('production')) {
            $announcementsData =  DB::table('announcements')
                ->selectRaw("count(case when created_at >='". $firstDayYear ."' AND created_at <='".$lastDateYear."' then 1 end) as this_year_count")
                ->selectRaw("count(case when created_at >='". $firstDayMonth ."' AND created_at <='".$lastDayMonth."' then 1 end) as this_month_count")
                ->selectRaw("count(case when DATE(created_at) = CURRENT_DATE then 1 end) as this_day_count")
                ->first();
        } else {
            $announcementsData =  DB::table('announcements')
                ->selectRaw("count(case when created_at >='". $firstDayYear ."' AND created_at <='".$lastDateYear."' then 1 end) as this_year_count")
                ->selectRaw("count(case when created_at >='". $firstDayMonth ."' AND created_at <='".$lastDayMonth."' then 1 end) as this_month_count")
                ->selectRaw("count(case when DATE(created_at) = CURDATE() then 1 end) as this_day_count")
                ->first();
        }

        $title = 'Announcement Publish Report';
        $modelName = 'Announcement';


        return view('admin.information.pdf.announcementreport', compact('title', 'modelName', 'announcements' ,'announcementsData',
        'date_start', 'date_end', 'sort_column', 'sort_option'

    ));
    }

    public function reportProfile(Announcement $announcement)
    {
        $title = 'Report - No data';
        $description = 'No data';

        try {
            $announcement->load('likes', 'comments', 'type','announcement_pictures')->loadCount('likes', 'comments', 'announcement_pictures');
        } catch(\Illuminate\Database\QueryException $ex){
            return view('errors.404Report', compact('title', 'description'));
        }
        $title = 'Announcement Profile Report';
        $modelName = 'Announcement Profile';

        return view('admin.information.pdf.announcementpicture', compact('title', 'modelName', 'announcement'));
    }
}
