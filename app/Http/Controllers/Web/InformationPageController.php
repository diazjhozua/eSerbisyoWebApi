<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InformationPageController extends Controller
{
    //
    public function informationDashboard() {
        return view('dashboards.information');
    }

    public function missingReport() {
        return view('admin.missing-reports.index');
    }
}
