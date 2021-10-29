<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            if (Auth::user()->user_role_id == 9 || Auth::user()->user_role_id == 8) {
                Session::flush();
                Auth::logout();
                return redirect("login")->withErrors('Your account is does not have admin priviledges (barangay officials),
                please download the android application to access the application as a resident or biker.');
            } elseif (Auth::user()->user_role_id < 5) {
                return redirect()->intended('/admin/dashboard');
            } elseif(Auth::user()->user_role_id == 5) {
                return redirect()->intended('/admin/users');
            }
        }
        return redirect("login")->withErrors('Login details are not valid');
    }

    public function logout() {
        Session::flush();
        Auth::logout();

        return redirect("login");
    }
}
