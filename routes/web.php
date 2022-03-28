<?php

use App\Events\ReportNotification;
use App\Helper\Helper;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\User\HomeController;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;

use SMSGatewayMe\Client\ApiClient;
use SMSGatewayMe\Client\Configuration;
use SMSGatewayMe\Client\Api\MessageApi;
use SMSGatewayMe\Client\Model\SendMessageRequest;

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
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
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


Route::get('/sendSMS', function() {
    // Configure client
    $config = Configuration::getDefaultConfiguration();
    $config->setApiKey('Authorization', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJhZG1pbiIsImlhdCI6MTY0NTUzMDA5MywiZXhwIjo0MTAyNDQ0ODAwLCJ1aWQiOjkzMTI1LCJyb2xlcyI6WyJST0xFX1VTRVIiXX0.fYMteqJ81ns-Q5VveOyzNdZTGh-lkGsxTOqnQVUVKoA');
    $apiClient = new ApiClient($config);
    $messageClient = new MessageApi($apiClient);

    // Sending a SMS Message
    $sendMessageRequest1 = new SendMessageRequest([
        'phoneNumber' => '09560492498',
        'message' => 'tanga kaba',
        'deviceId' => 127363
    ]);

    // $sendMessageRequest2 = new SendMessageRequest([
    //     'phoneNumber' => '07791064781',
    //     'message' => 'test2',
    //     'deviceId' => 2
    // ]);
    $sendMessages = $messageClient->sendMessages([
        $sendMessageRequest1,
        // $sendMessageRequest2
    ]);
    print_r($sendMessages);
});

 function send_notification_FCM($notification_id, $title, $message, $id,$type) {

    $accesstoken = env('FCM_KEY');

    $URL = 'https://fcm.googleapis.com/fcm/send';


        $post_data = '{
            "to" : "' . $notification_id . '",
            "data" : {
              "body" : "",
              "title" : "' . $title . '",
              "type" : "' . $type . '",
              "id" : "' . $id . '",
              "message" : "' . $message . '",
            },
            "notification" : {
                 "body" : "' . $message . '",
                 "title" : "' . $title . '",
                  "type" : "' . $type . '",
                 "id" : "' . $id . '",
                 "message" : "' . $message . '",
                "icon" : "new",
                "sound" : "default"
                },

          }';
        // print_r($post_data);die;

    $crl = curl_init();

    $headr = array();
    $headr[] = 'Content-type: application/json';
    $headr[] = 'Authorization: ' . $accesstoken;
    curl_setopt($crl, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($crl, CURLOPT_URL, $URL);
    curl_setopt($crl, CURLOPT_HTTPHEADER, $headr);

    curl_setopt($crl, CURLOPT_POST, true);
    curl_setopt($crl, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);

    $rest = curl_exec($crl);

    if ($rest === false) {
        // throw new Exception('Curl error: ' . curl_error($crl));
        //print_r('Curl error: ' . curl_error($crl));
        $result_noti = 0;
    } else {

        $result_noti = 1;
    }

    //curl_close($crl);
    //print_r($result_noti);die;
    return $result_noti;
}

Route::get('/test2', function () {
    $tos = array("cWdshxLDSYCDSdX1pMoVw3:APA91bGzJMHlHSlee1MApmkij0dfxzb_1RBYqB5j2AeE1uiUWR-omEWXrP4IWH4NFOKiupOl_2KckVbVtIGvJrczkIKkq99yhlIYo0lYkXKNU8p8t5xzr5-Bt28Hq8nQflDBZSNNwwXP",
        "emZMkrVzScOsLWbgFeLeMV:APA91bFf3I6EgV6qNjOl1OjqYNSB4CZKZWVEmmKDiGUNl7UO63tx82hyl1r09Ge1yLvIpogUsl3mQDmGTRBTWrpkP7enO043-xezcltQ652CJRlYmmredmQ5AdcUijZD1aj9kwAF4QUA",
    );

    $data=array(
        'title'=>'Greetings',
        'body'=>'Hi, From PHP Script'
    );

    Helper::instance()->sendFCMNotification($tos,$data);
});


Route::get('/test', function () {
    function notify($to,$data){

        $api_key="AAAAsfAZjQY:APA91bE8v8n4MSIPho8vwiaeUUKeIGS-80iZAKPvvf3TNu0-OLKt0i7L-c1S7XjnWEzA-PrV_Ffy1oeWHlxxw7bdk6nS1rMwUusAsabMdzQW83lX1g1OwCSi6e8kyRT8Mh2B746-KdC4";
        $url="https://fcm.googleapis.com/fcm/send";
        $fields=json_encode(array('to'=>$to,'notification'=>$data));

        // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ($fields));

        $headers = array();
        $headers[] = 'Authorization: key ='.$api_key;
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
    }

    $to="cWdshxLDSYCDSdX1pMoVw3:APA91bGzJMHlHSlee1MApmkij0dfxzb_1RBYqB5j2AeE1uiUWR-omEWXrP4IWH4NFOKiupOl_2KckVbVtIGvJrczkIKkq99yhlIYo0lYkXKNU8p8t5xzr5-Bt28Hq8nQflDBZSNNwwXP";

    $data=array(
        'title'=>'Greetings',
        'body'=>'Hi, From PHP Script'
    );

    notify($to,$data);
    echo "Notification Sent";
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

    Route::get('audit-logs/reports/{date_start}/{date_end}/{sort_column}/{sort_option}/', [AuditLog::class, 'report'])->name('auditLogs.reports');
    // Route::post('audit-logs/reports', [AuditLog::class, 'report'])->name('auditLogs.reports');
    Route::get('audit-logs', [AuditLog::class, 'index'])->name('auditLogs');
    Route::get('staffs', [AdminStaff::class, 'adminStaff'])->name('staffs.adminStaff');
    Route::put('staffs/demoteStaff/{user}', [AdminStaff::class, 'demoteStaff'])->name('staffs.demoteStaff');
    Route::get('staffs/promote-users', [AdminStaff::class, 'users'])->name('staffs.users');
    Route::put('staffs/promote-users/{user}', [AdminStaff::class, 'promoteUser'])->name('staffs.promoteUser');
});


Route::get('view/{folderName}/{fileName}', function ($folderName, $fileName) {

    if(file_exists(Storage::disk('cloudinary')->path($folderName.'/'.$fileName))){
        $url = Storage::disk('cloudinary')->path($folderName.'/'.$fileName);
        return response()->download($url);
    }else{
        return view('errors.NOFILE');
    }
})->name('viewFiles');

Route::get('download/{folderName}/{fileName}', function ($folderName, $fileName) {

    if(file_exists(Storage::disk('cloudinary')->path($folderName.'/'.$fileName))){
        $url = Storage::disk('cloudinary')->path($folderName.'/'.$fileName);
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



