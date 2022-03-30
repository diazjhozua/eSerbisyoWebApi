<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AnnouncementRequest;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\AnnouncementResource;
use App\Http\Resources\ApiAnnouncementResource;
use App\Http\Resources\CommentResource;
use App\Http\Resources\LikeResource;
use App\Models\Announcement;


class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('type','announcement_pictures')->withCount('comments', 'likes')->with('likes')->orderBy('id', 'DESC')->get();
        return ApiAnnouncementResource::collection($announcements)->additional(['success' => true]);
    }

    public function getLikeList(Announcement $announcement) {
        activity()->disableLogging();
        $announcement = $announcement->load('likes');
        $likes = $announcement->likes;
        return (LikeResource::collection($likes));
    }

    public function like(Announcement $announcement) {
        $like = $announcement->likes()->where('user_id', auth('api')->user()->id)->get();
        if (count($like)>0) {
            $like->each->delete();
            $isLike = false;
        } else {
            $like = $announcement->likes()->create(['user_id' => auth('api')->user()->id]);
            $isLike = true;
        }
        return Helper::instance()->likeStatus('announcement', $isLike);
    }

    public function getCommentList(Announcement $announcement) {
        $announcement = $announcement->load('comments');
        $comments = $announcement->comments;
        return (CommentResource::collection($comments));
    }

    public function comment(CommentRequest $request, Announcement $announcement) {
        activity()->disableLogging();
        $comment = $announcement->comments()->create(array_merge($request->validated(), ['user_id' => auth('api')->user()->id]));
        return (new CommentResource($comment))->additional(Helper::instance()->storeSuccess('comment'));
    }

    public function show(Announcement $announcement)
    {
        return (new ApiAnnouncementResource($announcement->load('likes', 'comments', 'type','announcement_pictures')->loadCount('likes', 'comments')))->additional(Helper::instance()->itemFound('announcement'));
    }

    // public function create()
    // {
    //     $types = Type::where('model_type', 'Announcement')->get();
    //     return ['types' => TypeResource::collection($types), 'success' => true];
    // }

    // public function store(AnnouncementRequest $request)
    // {
    //     $announcement = Announcement::create($request->getData());
    //     $announcement->comments_count = 0;
    //     $announcement->likes_count = 0;
    //     if (isset($request->picture_list)) {
    //         foreach ($request->picture_list as $key => $value) {
    //             $fileName = time().'_'.$value['picture']->getClientOriginalName();
    //             $filePath =   $value['picture']->storeAs('announcements', $fileName, 'public');
    //             AnnouncementPicture::create(['announcement_id' => $announcement->id, 'picture_name' => $fileName,'file_path' => $filePath]);
    //         }
    //     }
    //     return (new AnnouncementResource($announcement->load('type','announcement_pictures')))->additional(Helper::instance()->storeSuccess('announcement'));
    // }

    // public function edit(Announcement $announcement)
    // {
    //     $types = Type::where('model_type', 'Announcement')->get();
    //     return (new AnnouncementResource($announcement->load('type')))->additional(array_merge(['types' => TypeResource::collection($types)],Helper::instance()->itemFound('announcement')));
    // }

    // public function update(AnnouncementRequest $request, Announcement $announcement)
    // {
    //     $announcement->fill($request->getData())->save();
    //     return (new AnnouncementResource($announcement->load('likes', 'comments', 'type','announcement_pictures')->loadCount('likes', 'comments')))->additional(Helper::instance()->updateSuccess('announcement'));
    // }

    // public function destroy(Announcement $announcement)
    // {
    //     return DB::transaction(function() use ($announcement) {
    //         $announcement->load('announcement_pictures');
    //         foreach ($announcement->announcement_pictures as $picture) { Storage::delete('public/announcements/'. $picture->picture_name); }
    //         AnnouncementPicture::where('announcement_id', $announcement->id)->delete();
    //         $announcement->comments()->delete();
    //         $announcement->delete();
    //         return response()->json(Helper::instance()->destroySuccess('announcement'));
    //     });
    // }


}
