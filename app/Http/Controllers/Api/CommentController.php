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
        return (new CommentResource($comment))->additional(Helper::instance()->itemFound('comment'));
    }

    public function update(CommentRequest $request, Comment $comment)
    {
        $comment->fill($request->validated())->save();
        return (new CommentResource($comment))->additional(Helper::instance()->updateSuccess('comment'));
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return response()->json(Helper::instance()->destroySuccess('comment'));
    }
}
