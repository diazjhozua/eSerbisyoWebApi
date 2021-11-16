<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Web\Taskforce\ {
    ReportTypeController as ReportTypeCtrl,
    ComplaintTypeController as ComplaintTypeCtrl,
    ReportController as ReportCtrl,
    MissingPersonController as MissingPersonCtrl
};

// For Super Admin, Info Admin, and Info Staff
Route::prefix('admin')->name('admin.')->middleware(['auth', 'isAdmin:taskForceAdmin'])->group(function () {

    Route::resource('report-types', ReportTypeCtrl::class)->except(['create']);
    Route::resource('complaint-types', ComplaintTypeCtrl::class)->except(['create']);

    Route::put('reports/{report}/respond',  [ReportCtrl::class, 'respond']);
    Route::resource('reports', ReportCtrl::class)->only(['index', 'show']);

    Route::post('missing-persons/{missing_person}/comment',  [MissingPersonCtrl::class, 'comment']);
    Route::put('missing-persons/change-status/{missing_person}', [MissingPersonCtrl::class, 'changeStatus']);
    Route::resource('missing-persons', MissingPersonCtrl::class);

});


