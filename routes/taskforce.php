<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Web\Taskforce\ {
    ReportTypeController as ReportTypeCtrl,
    ComplaintTypeController as ComplaintTypeCtrl,
    ReportController as ReportCtrl,
    MissingPersonController as MissingPersonCtrl,
    MissingItemController as MissingItemCtrl,
    ComplaintController as ComplaintCtrl,
};

// For Super Admin, Info Admin, and Info Staff
Route::prefix('admin')->name('admin.')->middleware(['auth', 'isAdmin:taskForceAdmin'])->group(function () {

    Route::resource('report-types', ReportTypeCtrl::class)->except(['create']);
    Route::resource('complaint-types', ComplaintTypeCtrl::class)->except(['create']);

    Route::put('reports/{report}/respond',  [ReportCtrl::class, 'respond']);
    Route::resource('reports', ReportCtrl::class)->only(['index', 'show']);

    Route::put('missing-persons/change-status/{missing_person}', [MissingPersonCtrl::class, 'changeStatus']);
    Route::resource('missing-persons', MissingPersonCtrl::class);

    Route::put('missing-items/change-status/{missing_item}', [MissingItemCtrl::class, 'changeStatus']);
    Route::resource('missing-items', MissingItemCtrl::class);

    Route::resource('complaints', ComplaintCtrl::class);

});


