<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Web\Admin\ {
    DashboardController as AdminDashboard,
    StaffController as AdminStaff,
};

if (App::environment('production')) {
    URL::forceScheme('https');
}

// For Super Admin and all Admin
Route::prefix('admin')->name('admin.')->middleware(['auth', 'isAdmin:superAdmin'])->group(function () {
    Route::get('information-dashboard', [AdminDashboard::class, 'informationAdmin'])->name('dashboard.information');
    Route::get('certificate-dashboard', [AdminDashboard::class, 'certificationAdmin'])->name('dashboard.certificate');
    Route::get('taskforce-dashboard', [AdminDashboard::class, 'taskforceAdmin'])->name('dashboard.taskforce');

    Route::put('staffs/promote/{user}', [AdminStaff::class, 'promoteToAnyPosition'])->name('staffs.promoteToAnyPosition');
});
