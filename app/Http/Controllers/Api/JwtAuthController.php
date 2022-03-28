<?php

namespace App\Http\Controllers\Api;

use App\Events\UserVerificationEvent;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ApiLoginRequest;
use App\Http\Requests\Api\ChangeEmailRequest;
use App\Http\Requests\Api\ChangePasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\Api\RegistrationRequest;
use App\Http\Requests\Api\UserInfoRequest;
use App\Http\Requests\Api\UserVerificationRequest;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\UserProfileResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserVerificationResource;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserVerification;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Hash;
use Helper;
use Illuminate\Http\Request;
use Log;
use Storage;

class JwtAuthController extends Controller
{
    public function checkSameDeviceId($deviceID) {
        activity()->disableLogging();
        User::where('device_id', $deviceID)->each(function ($user) {
            $user->device_id = NULL;
            $user->save();
        });
    }

    public function login(ApiLoginRequest $request){
        activity()->disableLogging();
        $credentials = $request->only('email', 'password');
        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['message' => 'Login details are not valid' ], 401);
        }
        $this->checkSameDeviceId($request->device_id);
        // update device id
        User::find(auth('api')->user()->id)->update(['device_id'=> $request->device_id]);

        return $this->generateToken($token);
    }

    public function register(RegistrationRequest $request) {
        activity()->disableLogging();

        $this->checkSameDeviceId($request->device_id);
        User::create(array_merge($request->validated(), ['password' => Hash::make($request->password), 'user_role_id' => 9]));
        $credentials = $request->validated();
        $token = auth('api')->attempt($credentials);
        return $this->generateToken($token);
    }

    // change password
    public function changePassword(ChangePasswordRequest $request) {
        activity()->disableLogging();
        User::find(auth('api')->user()->id)->update(['password'=> Hash::make($request->new_password)]);
        return response()->json(Helper::instance()->updateSuccess('user password'));
    }

    // change email
    public function changeEmail(ChangeEmailRequest $request) {
        activity()->disableLogging();
        User::find(auth('api')->user()->id)->update(['email'=> $request->new_email]);
        return response()->json(array_merge(['email' => $request->new_email ], Helper::instance()->updateSuccess('user email')));
    }

    public function updateUserInfo(UserInfoRequest $request) {
        activity()->disableLogging();
        $user = User::findOrFail(auth('api')->user()->id);
        if($request->picture != ''){
            if($user->picture_name != '') {
                Cloudinary::destroy($user->picture_name);
            }
            $result = cloudinary()->uploadFile('data:image/jpeg;base64,'.$request->picture, ['folder' => env('CLOUDINARY_PATH', 'dev-barangay')]);
            $user->fill(array_merge($request->getData(), ['picture_name' => $result->getPublicId(),'file_path' => $result->getPath()]))->save();
        } else {
            $user->fill($request->getData())->save();
        }
        return response()->json([
            'data' => $user,
            'message' => 'user profile is successfully updated',
        ], 200);

        // return (new UserProfileResource($user))->additional(Helper::instance()->updateSuccess('user profile'));
    }

    // get latest verification request submitted by the authenticated user
    public function myVerificationRequest() {
        $userVerification = UserVerification::with('user')->where('user_id', auth('api')->user()->id)->orderBy('created_at','DESC')->first();

        if ($userVerification == null) {
            return response()->json(['isEmpty' => true], 200);
        }

        return response()->json(['isEmpty' => false, 'data' => new UserVerificationResource($userVerification)], 200);
    }

    public function submitVerificationRequest(UserVerificationRequest $request) {
        activity()->disableLogging();
        $userVerification = UserVerification::with('user')->where('user_id', auth('api')->user()->id)->where('status', 'Pending')->orderBy('created_at','DESC')->first();

        if ($userVerification != null) {
            return response()->json(['message' => 'You have already submitted a request. Please wait for the administrator to respond to your current request' ], 406);
        }

        $result = cloudinary()->uploadFile('data:image/jpeg;base64,'.$request->picture, ['folder' => env('CLOUDINARY_PATH', 'dev-barangay')]);

        $userVerification = UserVerification::create([
            'user_id' =>  auth('api')->user()->id,
            'credential_name' => $result->getPublicId(),
            'credential_file_path' => $result->getPath(),
            'status' => 'Pending'
        ]);

        event(new UserVerificationEvent($userVerification->load('user.purok')));

        return (new UserVerificationResource($userVerification))->additional(Helper::instance()->storeSuccess('user_verification_request'));
    }

    /**
     * Sign out
    */
    public function logout() {
        auth('api')->logout();
        return response()->json(['message' => 'User loged out'], 200);
    }

    /**
     * Token refresh
    */
    public function refresh() {
        // return $this->generateToken(auth()->refresh());
    }

    /**
     * User
    */
    public function user() {
        return response()->json([
            'data' => auth('api')->user()
        ], 200);
    }

    /**
     * Generate token
    */
    protected function generateToken($token){
        if (auth('api')->user()->status == 'Enable') {
            return response()->json([
                'message' => 'Authentication Success',
                'access_token' => $token,
                'token_type' => 'bearer',
                'user' => auth('api')->user()
            ], 200);
        } else {
            return response()->json([
                'message' => 'Your account has been disabled by the administrator. Reason: '.auth('api')->user()->admin_status_message.'.Please go to the barangay to enable your account again.',
            ], 403);
        }
    }


    // subscribe or unsubsribed
    public function subscribe() {
        $user = User::find(auth('api')->user()->id);
        $is_subscribed = $user->is_subscribed == "Yes" ? "No" : "Yes";

        $user->fill(['is_subscribed' => $is_subscribed])->save();

        return response()->json([
            'message' => $is_subscribed == "Yes" ? "Subscribed successfully!" : "Unsubscribed successfully!",
        ], 200);
    }

    // get auth notification list
    public function myNotifications() {
        $notifications = Notification::where("user_id", auth('api')->user()->id)->orderBy('created_at', 'DESC')->get();

        return NotificationResource::collection($notifications)->additional(['is_subscribed' => auth('api')->user()->is_subscribed]);
    }

    // get auth notification count
    public function getNotificationsCount() {
        $notifications = Notification::where("user_id", auth('api')->user()->id)->where('is_seen', 'No')->orderBy('created_at', 'DESC')->count();

        return response()->json([
            'notificationCount' => $notifications,
        ], 201);
    }

    // marked the notification as seen when the user click the specific notification
    public function seenNotification(Notification $notification) {
        if ($notification->user_id != auth('api')->user()->id) {
            return response()->json([
                'message' => 'You cannot view the other user\'s notification',
            ], 403);
        }

        $notification->fill(['is_seen' => "Yes"])->save();

        return response()->json([
            'message' => 'Ok',
        ], 201);
    }
}
