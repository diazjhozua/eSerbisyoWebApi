<?php

use App\Events\ReportNotification;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\User\HomeController;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;

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
use App\Models\Order;
use App\Models\Report;
use Barryvdh\Debugbar\Facade as Debugbar;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Pusher\Pusher;


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

// LANDING PAGE
Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('/downloads', [HomeController::class, 'downloads'])->name('download');
Route::get('/terms', [HomeController::class, 'terms'])->name('term');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');

// Route::get('/brgindigency', function () {
//     // $pdf = PDF::loadView('admin.certificates.brgindigency')->setOptions(['defaultFont' => 'sans-serif'])->setPaper('a4', 'portrait');
//     // return $pdf->stream();
//     return view('admin.certificates.brgindigency');
// });
// Route::get('/brgclear', function () {
//     return view('admin.certificates.brgclear');
// });

Route::get('/testing', function () {
    // feedbacks scheduler
    // dd(Report::where('created_at', '<=', Carbon::now()->subDay(1)->toDateTimeString())->where('status', 'Pending')->get());
    // dd(Report::where([['created_at', '<=', Carbon::now()->subDay(1)->toDateTimeString()], ['status', '=', 'Pending']])->get());
    // dd(Feedback::where([['created_at', '<=', Carbon::now()->subDay(10)->toDateTimeString()], ['status', '=', 'Pending']])->get());
        // dd(Order::find(5));
    dd(
        Order::where('pickup_date', '<=', Carbon::now()->subDays(3)->toDateTimeString())
            ->where('application_status', 'Approved')
            ->where('pick_up_type', 'Delivery')
            ->where('order_status', 'Accepted')
        ->get()
    );
    dd( Order::where([
            ['pickup_date', '=', Carbon::today()], ['application_status', '==', 'Approved'],
            ['pick_up_type', '==', 'Pickup'], ['order_status', '==', 'Waiting']
        ])->get());
    // return view('admin.certificates.cedula');
});

// Route::get('/brgid', function () {
//     return view('admin.certificates.brgid');
// });
// Route::get('/busclear', function () {
//     return view('admin.certificates.busclear');
// });
// Route::get('/brgcedula', function () {
//     return view('admin.certificates.brgcedula');
// });

// Route::get('/checkout', function () {
//     // $pdf = PDF::loadView('admin.certificates.brgindigency')->setOptions(['defaultFont' => 'sans-serif'])->setPaper('a4', 'portrait');
//     // return $pdf->stream();
//     return view('admin.certification.orders.show');
// });

// Route::get('/event', function () {
//     event(new ReportNotification('This is our first broadcast message'));
// });

// Route::post('/pusher/auth', function (Request $request) {
//         $user = auth()->user();
//         $socket_id = $request->socket_id;
//         $channel_name =$request->channel_name;
//         $key = getenv('PUSHER_APP_KEY');
//         $secret = getenv('PUSHER_APP_SECRET');
//         $app_id = getenv('PUSHER_APP_ID');

//         if ($user) {

//             $pusher = new Pusher($key, $secret, $app_id);
//             $auth = $pusher->socket_auth($channel_name, $socket_id);

//             return response($auth, 200);

//         } else {
//             header('', true, 403);
//             echo "Forbidden";
//             return;
//         }
// });

// Route::get('/', function () {

//     return view('admin.taskforce.sample');
// });


Route::group(['guard' => 'web'], function () {
    Route::get('login', [AuthController::class, 'index'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.authenticate');
    Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register.get');
    Route::post('register', [AuthController::class, 'submitRegisterForm'])->name('register.post');
});

Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('forget-password', [AuthController::class, 'showForgetPassword'])->name('forget.password.get');
Route::post('forget-password', [AuthController::class, 'submitForgetPasswordForm'])->name('forget.password.post');
Route::get('reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('reset-password', [AuthController::class, 'submitResetPasswordForm'])->name('reset.password.post');

// For Super Admin and all Admin
Route::prefix('admin')->name('admin.')->middleware(['auth', 'isAdmin:admin'])->group(function () {
    Route::get('users', [AdminUser::class, 'index'])->name('users.index');
    Route::put('users/changeStatus/{user}', [AdminUser::class, 'changeUserStatus']);
    Route::get('users/viewUserVerification/{user_verification}', [AdminUser::class, 'viewUserVerification']);
    Route::put('users/verifyUser/{user_verification}', [AdminUser::class, 'verifyUser']);

    Route::get('dashboard', [AdminDashboard::class, 'index'])->name('dashboard.index');
    Route::post('audit-logs/reports', [AuditLog::class, 'report'])->name('auditLogs.reports');
    Route::get('audit-logs', [AuditLog::class, 'index'])->name('auditLogs');
    Route::get('staffs', [AdminStaff::class, 'adminStaff'])->name('staffs.adminStaff');
    Route::put('staffs/demoteStaff/{user}', [AdminStaff::class, 'demoteStaff'])->name('staffs.demoteStaff');
    Route::get('staffs/promote-users', [AdminStaff::class, 'users'])->name('staffs.users');
    Route::put('staffs/promote-users/{user}', [AdminStaff::class, 'promoteUser'])->name('staffs.promoteUser');
});


Route::get('view/{folderName}/{fileName}', function ($folderName, $fileName) {

    if(file_exists(Storage::disk('public')->path($folderName.'/'.$fileName))){
        $url = Storage::disk('public')->path($folderName.'/'.$fileName);
        return response()->download($url);
    }else{
        return view('errors.NOFILE');
    }
})->name('viewFiles');

Route::get('download/{folderName}/{fileName}', function ($folderName, $fileName) {

    if(file_exists(Storage::disk('public')->path($folderName.'/'.$fileName))){
        $url = Storage::disk('public')->path($folderName.'/'.$fileName);
        return response()->download($url);
    }else{
        return view('errors.NOFILE');
    }
})->name('downloadFiles');


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
        if(file_exists(Storage::disk('public')->path($folderName.'/'.$fileName))){
            $url = Storage::disk('public')->path($folderName.'/'.$fileName);
            return response()->file($url);
        }else{
            return view('errors.NOFILE');
        }
    })->name('viewFiles');
});



