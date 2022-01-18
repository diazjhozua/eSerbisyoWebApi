<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ChangeEmailRequest;
use App\Http\Requests\Api\ChangePasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\Api\RegistrationRequest;
use App\Http\Requests\Api\UserInfoRequest;
use App\Http\Resources\UserProfileResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Hash;
use Helper;
use Illuminate\Http\Request;
use Log;
use Storage;

class JwtAuthController extends Controller
{

    public function login(LoginRequest $request){
        $credentials = $request->validated();

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['message' => 'Login details are not valid' ], 401);
        }
        return $this->generateToken($token);
    }


    public function register(RegistrationRequest $request) {
        User::create(array_merge($request->validated(), ['password' => Hash::make($request->password), 'user_role_id' => 9]));
        $credentials = $request->validated();
        $token = auth('api')->attempt($credentials);
        return $this->generateToken($token);
    }

    // change password
    public function changePassword(ChangePasswordRequest $request) {
        User::find(auth('api')->user()->id)->update(['password'=> Hash::make($request->new_password)]);
        return response()->json(Helper::instance()->updateSuccess('user password'));
    }

    // change email
    public function changeEmail(ChangeEmailRequest $request) {
        User::find(auth('api')->user()->id)->update(['email'=> $request->new_email]);
        return response()->json(array_merge(['email' => $request->new_email ], Helper::instance()->updateSuccess('user email')));
    }

    public function updateUserInfo(UserInfoRequest $request) {
        $user = User::findOrFail(auth('api')->user()->id);
        $user->fill($request->getData())->save();
        if($request->picture != ''){
            if($user->picture_name != '') {
                Storage::delete('public/users/'. $user->picture_name);
            }

            $fileName = uniqid().time().'.jpg';
            $filePath = 'users/'.$fileName;

            Storage::disk('public')->put($filePath, base64_decode($request->picture));
            $user->fill(array_merge($request->getData(), ['picture_name' => $fileName,'file_path' => $filePath]))->save();

        } else {
            $user->fill($request->getData())->save();
        }
        return (new UserProfileResource($user))->additional(Helper::instance()->updateSuccess('user profile'));
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
        return (new UserResource(auth('api')->user()));
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
}
