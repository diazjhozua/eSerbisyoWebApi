<?php

namespace App\Http\Controllers\Api;

use App\Events\MissingItemEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\Api\MissingItemRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\MissingItemResource;
use App\Models\MissingItem;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Helper;
use Illuminate\Http\Request;
use Storage;

class MissingItemController extends Controller
{
    public function index()
    {
        // fetch all approved missing person
        $missingItems = MissingItem::withCount('comments')->where('status', 'Approved')->orderBy('created_at','DESC')->get();
        return MissingItemResource::collection($missingItems)->additional(['success' => true]);
    }

    public function authReports()
    {
        // fetch all authenticated missing person item
        $missingItems = MissingItem::withCount('comments')->where('contact_user_id', auth('api')->user()->id)->orderBy('created_at','DESC')->get();
        return MissingItemResource::collection($missingItems)->additional(['success' => true]);
    }

    public function getCommentList(MissingItem $missingItem) {
        $missingItem = $missingItem->load('comments');
        $comments = $missingItem->comments;
        return (CommentResource::collection($comments));
    }

    public function comment(CommentRequest $request, MissingItem $missingItem) {
        activity()->disableLogging();
        $comment = $missingItem->comments()->create(array_merge($request->validated(), ['user_id' => auth('api')->user()->id]));
        return (new CommentResource($comment))->additional(Helper::instance()->storeSuccess('comment'));
    }

    public function store(MissingItemRequest $request)
    {
        activity()->disableLogging();
        $picture = cloudinary()->uploadFile('data:image/jpeg;base64,'.$request->picture, ['folder' => 'barangay']);
        $missingFileName = $picture->getPublicId();
        $missingFilePath = $picture->getPath();

        $credential = cloudinary()->uploadFile('data:image/jpeg;base64,'.$request->credential, ['folder' => 'barangay']);
        $credentialFileName = $credential->getPublicId();
        $credentialFilePath = $credential->getPath();

        $missingItem = MissingItem::create(array_merge($request->getData(),
            [
            'user_id' => auth('api')->user()->id, 'contact_user_id' => auth('api')->user()->id, 'status' => 'Pending',
            'picture_name' => $missingFileName, 'file_path' => $missingFilePath,
            'credential_name' => $credentialFileName, 'credential_file_path' => $credentialFilePath,
            ]
        ));

        $missingItem->comments_count = 0;
        event(new MissingItemEvent($missingItem));
        return (new MissingItemResource($missingItem))->additional(Helper::instance()->storeSuccess('missing item report'));

    }

    public function edit(MissingItem $missingItem)
    {
        if ($missingItem->contact_user_id != auth('api')->user()->id) {
            return response()->json(["message" => "You can only edit your reports."], 403);
        }

        return (new MissingItemResource($missingItem->loadCount('comments')));
    }

    public function update(MissingItemRequest $request, MissingItem $missingItem)
    {

        activity()->disableLogging();
        if ($missingItem->contact_user_id != auth('api')->user()->id) {
            return response()->json(["message" => "You can only update your reports."], 403);
        }

        $missingFileName = $missingItem->picture_name;
        $missingFilePath = $missingItem->file_path;
        $credentialFileName = $missingItem->credential_name;
        $credentialFilePath = $missingItem->credential_file_path;

        if ($request->picture != '') {
            if($missingItem->picture_name != '') {
                Cloudinary::destroy($missingItem->picture_name);
            }

            $picture = cloudinary()->uploadFile('data:image/jpeg;base64,'.$request->picture, ['folder' => 'barangay']);
            $missingFileName = $picture->getPublicId();
            $missingFilePath = $picture->getPath();
        }

        if ($request->credential != '') {
            if($missingItem->credential_name != '') {
                Cloudinary::destroy($missingItem->credential_name);
            }
            $credential = cloudinary()->uploadFile('data:image/jpeg;base64,'.$request->credential, ['folder' => 'barangay']);
            $credentialFileName = $credential->getPublicId();
            $credentialFilePath = $credential->getPath();
        }

        $missingItem->fill(array_merge($request->getData(),             [
            'status' => 'Pending',
            'picture_name' => $missingFileName, 'file_path' => $missingFilePath,
            'credential_name' => $credentialFileName, 'credential_file_path' => $credentialFilePath,
            ]))->save();

        return (new MissingItemResource($missingItem->load('comments')->loadCount('comments')))->additional(Helper::instance()->updateSuccess('missing item report'));
    }

    public function destroy(MissingItem $missingItem)
    {
        activity()->disableLogging();
        if ($missingItem->contact_user_id != auth('api')->user()->id) {
            return response()->json(["message" => "You can only delete your reports."], 403);
        }
        Cloudinary::destroy($missingItem->picture_name);
        Cloudinary::destroy($missingItem->credential_name);
        $missingItem->delete();
        return response()->json(Helper::instance()->destroySuccess('missing item report'));
    }

}
