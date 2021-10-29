<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Web\AuthController;

use App\Http\Controllers\Web\Admin\ {
    UserController as AdminUser,
    DashboardController as AdminDashboard,
};


use Illuminate\Support\Facades\Storage;


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

// Route::get('/', function () {

// });

Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.authenticate');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');


// For Super Admin and all Admin
Route::prefix('admin')->name('admin.')->middleware(['auth', 'isAdmin:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard.index');
});


// For all admin and staff
Route::prefix('admin')->name('admin.')->middleware(['auth', 'isAdmin:notBasicUser'])->group(function () {

    // view profile of the auth user
    Route::get('users/profile/', [AdminUser::class, 'profile'])->name('users.profile');
    // edit profile of the auth user
    Route::get('users/profile/editProfile', [AdminUser::class, 'editProfile'])->name('users.editProfile');
    // update profile of the auth user
    Route::put('users/profile/updateProfile', [AdminUser::class, 'updateProfile'])->name('users.updateProfile');

    Route::get('files/{folderName}/{fileName}', function ($folderName, $fileName) {
        $url = Storage::disk('public')->path($folderName.'/'.$fileName);
        return response()->file($url);
    })->name('viewFiles');
});


