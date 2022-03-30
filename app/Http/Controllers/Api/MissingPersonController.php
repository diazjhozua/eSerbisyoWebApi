<?php

namespace App\Http\Controllers\Api;

use App\Events\MissingPersonEvent;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MissingPersonRequest;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;

use App\Http\Resources\MissingPersonResource;
use App\Jobs\SendMailJob;
use App\Jobs\SendSingleNotificationJob;
use App\Jobs\SMSJob;
use App\Models\MissingPerson;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Storage;

class MissingPersonController extends Controller
{
    public function index()
    {
        // fetch all approved missing person
        $missing_persons = MissingPerson::withCount('comments')->where('status', 'Approved')->orderBy('id','DESC')->get();
        return MissingPersonResource::collection($missing_persons)->additional(['success' => true]);
    }

    public function authReports()
    {
        // fetch all authenticated missing person item
        $missing_persons = MissingPerson::withCount('comments')->where('contact_user_id', auth('api')->user()->id)->orderBy('created_at','DESC')->get();
        return MissingPersonResource::collection($missing_persons)->additional(['success' => true]);
    }

    public function show(MissingPerson $missingPerson) {
        return (new MissingPersonResource($missingPerson->load('comments')->loadCount('comments')));
    }

    public function getCommentList(MissingPerson $missingPerson) {
        $missingPerson = $missingPerson->load('comments');
        $comments = $missingPerson->comments;
        return (CommentResource::collection($comments));
    }

    public function comment(CommentRequest $request, MissingPerson $missingPerson) {
        activity()->disableLogging();

        if ($missingPerson->contact_user_id != auth('api')->user()->id) {
            $subject = 'Missing Person Comment Notification';
            $emailMessage = auth('api')->user()->first_name.' '.auth('api')->user()->last_name.' commented on your missing person report #'.$missingPerson->id.'. <br> <br>  Comment: '.$request->body;
            $smsMessage = auth('api')->user()->first_name.' '.auth('api')->user()->last_name.' commented on your missing person report #'.$missingPerson->id.'.'.PHP_EOL.PHP_EOL.'Comment: '.$request->body.PHP_EOL.PHP_EOL.'-Barangay Cupang';

            // send app, sms and email notification
            dispatch(
                new SendSingleNotificationJob(
                    $missingPerson->contact->device_id, $missingPerson->contact->id, "Missing Person Comment Notification",
                    auth('api')->user()->first_name.' '.auth('api')->user()->last_name.' commented on your missing person report #'.$missingPerson->id, $missingPerson->id,  "App\Models\MissingPerson"
            ));

            dispatch(new SendMailJob($missingPerson->email, $subject, $emailMessage));
            dispatch(new SMSJob($missingPerson->phone_no, $smsMessage));
        }

        $comment = $missingPerson->comments()->create(array_merge($request->validated(), ['user_id' => auth('api')->user()->id]));
        return (new CommentResource($comment))->additional(Helper::instance()->storeSuccess('comment'));
    }

    public function store(MissingPersonRequest $request)
    {

        activity()->disableLogging();
        $picture = cloudinary()->uploadFile('data:image/jpeg;base64,'.$request->picture, ['folder' => env('CLOUDINARY_PATH', 'dev-barangay')]);
        $missingFileName = $picture->getPublicId();
        $missingFilePath = $picture->getPath();

        $credential = cloudinary()->uploadFile('data:image/jpeg;base64,'.$request->credential, ['folder' => env('CLOUDINARY_PATH', 'dev-barangay')]);
        $credentialFileName = $credential->getPublicId();
        $credentialFilePath = $credential->getPath();

        $missingPerson = MissingPerson::create(array_merge($request->getData(),
            [
            'user_id' => auth('api')->user()->id, 'contact_user_id' => auth('api')->user()->id, 'status' => 'Pending',
            'picture_name' => $missingFileName, 'file_path' => $missingFilePath,
            'credential_name' => $credentialFileName, 'credential_file_path' => $credentialFilePath,
            ]
        ));

        $missingPerson->comments_count = 0;
        event(new MissingPersonEvent($missingPerson));
        return (new MissingPersonResource($missingPerson))->additional(Helper::instance()->storeSuccess('missing person report'));

    }

    public function edit(MissingPerson $missingPerson)
    {
        if ($missingPerson->contact_user_id != auth('api')->user()->id) {
            return response()->json(["message" => "You can only edit your reports."], 403);
        }
        return (new MissingPersonResource($missingPerson->loadCount('comments')));
    }

    public function update(MissingPersonRequest $request, MissingPerson $missingPerson)
    {
        if ($missingPerson->contact_user_id != auth('api')->user()->id) {
            return response()->json(["message" => "You can only update your reports."], 403);
        }

        $missingFileName = $missingPerson->picture_name;
        $missingFilePath = $missingPerson->file_path;
        $credentialFileName = $missingPerson->credential_name;
        $credentialFilePath = $missingPerson->credential_file_path;

        if ($request->picture != '') {
            if($missingPerson->picture_name != '') {
                Cloudinary::destroy($missingPerson->picture_name);
            }
            $picture = cloudinary()->uploadFile('data:image/jpeg;base64,'.$request->picture, ['folder' => env('CLOUDINARY_PATH', 'dev-barangay')]);
            $missingFileName = $picture->getPublicId();
            $missingFilePath = $picture->getPath();
        }

        if ($request->credential != '') {
            if($missingPerson->credential_name != '') {
                Cloudinary::destroy($missingPerson->credential_name);
            }
            $credential = cloudinary()->uploadFile('data:image/jpeg;base64,'.$request->credential, ['folder' => env('CLOUDINARY_PATH', 'dev-barangay')]);
            $credentialFileName = $credential->getPublicId();
            $credentialFilePath = $credential->getPath();
        }

        $missingPerson->fill(array_merge($request->getData(),             [
            'status' => 'Pending',
            'picture_name' => $missingFileName, 'file_path' => $missingFilePath,
            'credential_name' => $credentialFileName, 'credential_file_path' => $credentialFilePath,
            ]))->save();

        return (new MissingPersonResource($missingPerson->load('comments')->loadCount('comments')))->additional(Helper::instance()->updateSuccess('missing person report'));
    }

    public function destroy(MissingPerson $missingPerson)
    {
        if ($missingPerson->contact_user_id != auth('api')->user()->id) {
            return response()->json(["message" => "You can only delete your reports."], 403);
        }

        Cloudinary::destroy($missingPerson->picture_name);
        Cloudinary::destroy($missingPerson->credential_name);
        $missingPerson->delete();
        return response()->json(Helper::instance()->destroySuccess('missing person report'));
    }
}
