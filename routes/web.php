<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Web\InformationPageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('dashboards.information');
});

Route::get('/admin/information/dashboard', [InformationPageController::class, 'informationDashboard'])->name('information-dashboard');
Route::get('/admin/information/missing-report', [InformationPageController::class, 'missingReport'])->name('missing-report');



