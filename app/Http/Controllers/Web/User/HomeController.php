<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Models\Android;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(){

        return view('admin.user.home');
    }

    public function downloads(){
        $androids = Android::orderBy('created_at', 'DESC')->get()->take(5);
        return view('admin.user.downloads', compact('androids'));
    }

    public function terms(){
        return view('admin.user.terms');

    }

    public function privacy(){
        return view('admin.user.privacy');

    }
}
