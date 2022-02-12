<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;

class CommentController extends Controller
{
    public function edit(Comment $comment)
    {
        if ($comment->user_id == auth('api')->user()->id) {
            return (new CommentResource($comment))->additional(Helper::instance()->itemFound('comment'));
        } else {
            return response()->json(["message" => "You can only edit your comment."], 403);
        }

    }

    public function update(CommentRequest $request, Comment $comment)
    {
        activity()->disableLogging();
        if ($comment->user_id == auth('api')->user()->id) {
            $comment->fill($request->validated())->save();
            return (new CommentResource($comment))->additional(Helper::instance()->updateSuccess('comment'));
        } else {
            return response()->json(["message" => "You can only update your comment."], 403);
        }
    }

    public function destroy(Comment $comment)
    {
        activity()->disableLogging();
        if ($comment->user_id == auth('api')->user()->id) {
            $comment->delete();
            return response()->json(Helper::instance()->destroySuccess('comment'));
        } else {
            return response()->json(["message" => "You can only delete your comment."], 403);
        }

    }
}
