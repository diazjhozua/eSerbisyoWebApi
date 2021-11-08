<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use Barryvdh\DomPDF\Facade as PDF;

use App\Http\Controllers\Web\Admin\ {
    UserController as AdminUser,
    StaffController as AdminStaff,
    ProfileController as AdminProfile,
    DashboardController as AdminDashboard,
    AuditLogController as AuditLog,
};
use App\Models\Announcement;
use App\Models\Document;
use App\Models\Feedback;
use Barryvdh\Debugbar\Facade as Debugbar;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;

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
    // $pdf = app('dompdf.wrapper');
    // $pdf->loadView('admin.pdf.invoice');
    // return $pdf->download('invoice.pdf');

    // $announcement = Announcement::with('likes', 'comments', 'type','announcement_pictures')
    //     ->withCount('likes', 'comments', 'announcement_pictures')->findOrFail(171);

    // $pdf = PDF::loadView('admin.reports.announcementProfile', compact('announcement'))->setOptions(['defaultFont' => 'sans-serif'])->setPaper('a4', 'landscape');
    // return $pdf->stream();

    $firstDayYear = date('Y-m-d', strtotime('first day of january this year'));
    $lastDateYear = date('Y-m-d', strtotime('last day of december this year'));

    $feedbacks = Feedback::with('type')
        ->whereBetween('created_at', [$firstDayYear, $lastDateYear])
            ->orderBy('id', 'DESC')
            ->where(function($query) {
                if(true) {

                }
                return $query
                ->where('status', '=', 'Pending');
            })
        ->get();

    $pdf = PDF::loadView('admin.information.reports.feedback', compact('feedbacks'))->setOptions(['defaultFont' => 'sans-serif'])->setPaper('a4', 'landscape');
    return $pdf->stream();




    // $documents = Document::with('type')->get();

    // $firstDayYear = date('Y-m-d', strtotime('first day of january this year'));
    // $lastDateYear = date('Y-m-d', strtotime('last day of december this year'));

    // // $documents = Document::with('type') // Do some querying..
    // //         ->whereBetween('created_at', [$firstDayYear, $lastDateYear])
    // //         ->orderBy('type.name', 'ASC')->get();

    // $documents = Document::with(['type'])
    //     ->select('documents.*')
    //     ->whereBetween('documents.created_at', [$firstDayYear, $lastDateYear])
    //     ->join('types', 'types.id', '=', 'documents.type_id')
    //     ->orderBy('types.name', 'ASC')
    //     ->get();


    // // dd($documents);
    // // $pdf = App::make('dompdf.wrapper');
    // // $pdf->loadView('admin.pdf.invoice', compact('documents'));


    // $pdf = PDF::loadView('admin.reports.document', compact('documents'))->setOptions(['defaultFont' => 'sans-serif']);
    // return $pdf->stream();

    // $pdf = PDF::loadView('admin.pdf.invoice', compact('documents'))->setOptions(['defaultFont' => 'sans-serif', 'isRemoteEnabled' => true]);;
    // return $pdf->stream();
    // return $pdf->download('itsolutionstuff.pdf');


    // return $pdf->download('invoice.pdf');


    // // $pdf = ::loadView('admin.pdf.invoice');
    // // return $pdf->download('invoice.pdf');

    // // // $activity = Activity::all()->last();
    // // // dd($activity->causer->user_role->role);

    // // $activity = Activity::all()->last();
    // // dd($activity);
});

Route::get('login', [AuthController::class, 'index'])->name('login');
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.authenticate');
Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register.get');
Route::post('register', [AuthController::class, 'submitRegisterForm'])->name('register.post');

Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('forget-password', [AuthController::class, 'showForgetPassword'])->name('forget.password.get');
Route::post('forget-password', [AuthController::class, 'submitForgetPasswordForm'])->name('forget.password.post');
Route::get('reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('reset-password', [AuthController::class, 'submitResetPasswordForm'])->name('reset.password.post');


// For Super Admin and all Admin
Route::prefix('admin')->name('admin.')->middleware(['auth', 'isAdmin:admin'])->group(function () {
    Route::get('dashboard', [AdminDashboard::class, 'index'])->name('dashboard.index');
    Route::post('audit-logs/reports', [AuditLog::class, 'report'])->name('auditLogs.reports');
    Route::get('audit-logs', [AuditLog::class, 'index'])->name('auditLogs');
    Route::get('staffs', [AdminStaff::class, 'adminStaff'])->name('staffs.adminStaff');
    Route::put('staffs/demoteStaff/{user}', [AdminStaff::class, 'demoteStaff'])->name('staffs.demoteStaff');
    Route::get('staffs/promote-users', [AdminStaff::class, 'users'])->name('staffs.users');
    Route::put('staffs/promote-users/{user}', [AdminStaff::class, 'promoteUser'])->name('staffs.promoteUser');
});


// For all admin and staff
Route::prefix('admin')->name('admin.')->middleware(['auth', 'isAdmin:notBasicUser'])->group(function () {

    // view profile of the auth user
    Route::get('profile/', [AdminProfile::class, 'index'])->name('profile.index');
    // edit profile of the auth user
    Route::get('profile/edit', [AdminProfile::class, 'edit'])->name('profile.edit');
    // update profile of the auth user
    Route::put('profile/update', [AdminProfile::class, 'update'])->name('profile.update');
    // change password
    Route::put('profile/changePassword', [AdminProfile::class, 'changePassword'])->name('profile.changePassword');
    // change email
    Route::put('profile/changeEmail', [AdminProfile::class, 'changeEmail'])->name('profile.changeEmail');


    Route::get('files/{folderName}/{fileName}', function ($folderName, $fileName) {
        $url = Storage::disk('public')->path($folderName.'/'.$fileName);
        return response()->file($url);
    })->name('viewFiles');
});


