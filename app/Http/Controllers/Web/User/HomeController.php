<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(){

        return view('admin.user.home');
    }
    
    public function downloads(){
        return view('admin.user.downloads');

    }
    
    public function terms(){
        return view('admin.user.terms');

    }
    
    public function privacy(){
        return view('admin.user.privacy');

    }
}
