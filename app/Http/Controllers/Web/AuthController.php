<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Log;
use Mail;
use Route;
use Str;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        Log::debug('hehehe');
        $credentials = $request->validated();
        if (Auth::attempt($credentials)) {
            if (Auth::user()->user_role_id == 9 || Auth::user()->user_role_id == 8) {
                Session::flush();
                Auth::logout();
                return response()->json(['message' => 'Your account does not have admin priviledges (barangay officials),
                please download the android application to access the application as a resident or biker.'], 403);

            } elseif (Auth::user()->user_role_id == 1) {
                return response()->json(['message' => 'Login success', 'route' => route('admin.dashboard.index')], 200);
            }  elseif (Auth::user()->user_role_id < 5) {
                return response()->json(['message' => 'Login success', 'route' => route('admin.dashboard.index')], 200);
            } elseif(Auth::user()->user_role_id == 5) {
                return response()->json(['message' => 'Login success', 'route' => route('admin.users.index')], 200);
            } elseif(Auth::user()->user_role_id == 6) {
                return response()->json(['message' => 'Login success', 'route' => route('admin.orders.index')], 200);
            } elseif(Auth::user()->user_role_id == 7) {
                return response()->json(['message' => 'Login success', 'route' => route('admin.reports.index')], 200);
            }
        }

        return response()->json(['message' => 'Login details are not valid' ], 401);
    }

    public function logout() {
        Session::flush();
        Auth::logout();

        return redirect("login");
    }

    public function showForgetPassword() {
        return view('auth.forgot-password');
    }

    public function submitForgetPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $token = Str::random(64);

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        Mail::send('emails.forgetPassword', ['token' => $token], function($message) use($request){
            $message->to($request->email);
            $message->subject('Reset Password');
        });

        return back()->withSuccess('We have e-mailed your password reset link! Please check your email for password reset link');
    }

    public function showResetPasswordForm($token) {
         return view('auth.reset-password', compact('token'));
    }

    public function submitResetPasswordForm(ResetPasswordRequest $request)
    {
        User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email'=> $request->email])->delete();

        return response()->json([
            'message' => 'Your password has been changed! If your are admin, please login on this page.
            For residents, please use the android application to login',
            'html' => 'Your password has been changed! If your are admin, please <a href="'.route('login').'">login</a> on this page.
            For residents, please use the android application to login',
        ], 200);
    }

    public function showRegisterForm() {
        return view('auth.register');
    }

    public function submitRegisterForm(RegistrationRequest $request) {
        User::create(array_merge($request->getData(), ['password' => Hash::make($request->password), 'user_role_id' => 8]));

        return response()->json([
            'message' => 'Registration Success! If you are a resident or biker, please use the android application to login your account. If you are an administrator, please wait for
            the other admin to approve your request',
        ], 200);
    }
}
