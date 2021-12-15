<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Web\Taskforce\ {
    ReportTypeController as ReportTypeCtrl,
    ComplaintTypeController as ComplaintTypeCtrl,
    ReportController as ReportCtrl,
    MissingPersonController as MissingPersonCtrl,
    MissingItemController as MissingItemCtrl,
    ComplaintController as ComplaintCtrl,
    ComplainantController as ComplainantCtrl,
    DefendantController as DefendantCtrl,
};


// For taskforce admin
Route::prefix('admin')->name('admin.')->middleware(['auth', 'isAdmin:taskForceAdmin'])->group(function () {

    Route::get('report-types/report/{date_start}/{date_end}/{sort_column}/{sort_option}', [ReportTypeCtrl::class, 'report'])->name('report-types.report');
    Route::get('report-types/reportShow/{id}/{date_start}/{date_end}/{sort_column}/{sort_option}/{classification_option}/{status_option}', [ReportTypeCtrl ::class, 'reportShow'])->name('report-types.reportShow');

    Route::get('complaint-types/report/{date_start}/{date_end}/{sort_column}/{sort_option}', [ComplaintTypeCtrl::class, 'report'])->name('complaint-types.report');
    Route::get('complaint-types/reportShow/{id}/{date_start}/{date_end}/{sort_column}/{sort_option}/{status_option}', [ComplaintTypeCtrl::class, 'reportShow'])->name('complaint-types.reportShow');

    Route::get('reports/report/{date_start}/{date_end}/{sort_column}/{sort_option}/{classification_option}/{status_option}', [ReportCtrl::class, 'report'])->name('reports.report');

    Route::get('missing-persons/report/{date_start}/{date_end}/{sort_column}/{sort_option}/{report_option}/{status_option}', [MissingPersonCtrl::class, 'report'])->name('missingPerson.report');
    Route::get('missing-items/report/{date_start}/{date_end}/{sort_column}/{sort_option}/{report_option}/{status_option}', [MissingItemCtrl::class, 'report'])->name('missingItem.report');

    Route::get('complaints/report/{date_start}/{date_end}/{sort_column}/{sort_option}/{status_option}', [ComplaintCtrl::class, 'report'])->name('missing-person.report');
});


// For taskforce admin/staff
Route::prefix('admin')->name('admin.')->middleware(['auth', 'isAdmin:taskForceStaff'])->group(function () {

    Route::resource('report-types', ReportTypeCtrl::class)->except(['create']);

    Route::resource('complaint-types', ComplaintTypeCtrl::class)->except(['create']);

    Route::put('reports/{report}/respond',  [ReportCtrl::class, 'respond']);
    Route::resource('reports', ReportCtrl::class)->only(['index', 'show']);

    Route::put('missing-persons/change-status/{missing_person}', [MissingPersonCtrl::class, 'changeStatus']);
    Route::resource('missing-persons', MissingPersonCtrl::class);

    Route::put('missing-items/change-status/{missing_item}', [MissingItemCtrl::class, 'changeStatus']);
    Route::resource('missing-items', MissingItemCtrl::class);

    Route::put('complaints/change-status/{complaint}', [ComplaintCtrl::class, 'changeStatus']);
    Route::resource('complaints', ComplaintCtrl::class);
    Route::resource('complainants', ComplainantCtrl::class)->except(['index', 'create', 'show']);
    Route::resource('defendants', DefendantCtrl::class)->except(['index', 'create', 'show']);

});


