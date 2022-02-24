<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ {
    FeedbackController,
    ReportController,
    UserRequirementController,
    DocumentController,
    EmployeeController,
    OrdinanceController,
    MissingPersonController,
    ComplaintController,
    ComplainantController,
    DefendantController,
    AnnouncementController,
    BikerController,
    ProjectController,
    CommentController,
    JwtAuthController as JwtAuthCtrl,
    MissingItemController,
    OrderController
};
use App\Http\Requests\CertificateFormRequest;


Route::post('/register', [JwtAuthCtrl::class, 'register']);
Route::post('/login', [JwtAuthCtrl::class, 'login']);

Route::post('/token-refresh', [JwtAuthCtrl::class, 'refresh']);
Route::get('/logout', [JwtAuthCtrl::class, 'logout']);

Route::group([
    'middleware' => 'jwtAuth:isBasicUser',
], function ($router) {
    Route::get('/myProfile', [JwtAuthCtrl::class, 'user']);
    Route::put('/changePassword', [JwtAuthCtrl::class, 'changePassword']);
    Route::put('/changeEmail', [JwtAuthCtrl::class, 'changeEmail']);
    Route::put('/updateProfile', [JwtAuthCtrl::class, 'updateUserInfo']);
    Route::get('/myProfile', [JwtAuthCtrl::class, 'user']);
    Route::get('/myVerificationRequest', [JwtAuthCtrl::class, 'myVerificationRequest']);
    Route::post('/submitVerificationRequest', [JwtAuthCtrl::class, 'submitVerificationRequest']);

    Route::get('feedbacks/getAnalytics', [FeedbackController::class, 'getAnalytics']);
    Route::get('feedbacks/', [FeedbackController::class, 'index']);
    Route::get('reports/getAnalytics', [ReportController::class, 'getAnalytics']);
    Route::get('reports/', [ReportController::class, 'index']);
    Route::resource('userRequirements', UserRequirementController::class)->except(['show', 'edit', 'update','delete']);

    Route::get('announcements/like/{announcement}',  [AnnouncementController::class, 'getLikeList']);
    Route::get('announcements/comment/{announcement}',  [AnnouncementController::class, 'getCommentList']);
    Route::post('announcements/like/{announcement}',  [AnnouncementController::class, 'like']);
    Route::post('announcements/comment/{announcement}',  [AnnouncementController::class, 'comment']);

    Route::resource('announcements', AnnouncementController::class)->only(['index']);
    Route::resource('comments', CommentController::class)->only(['edit', 'update', 'destroy']);

    Route::resource('documents', DocumentController::class)->only(['index']);
    Route::resource('ordinances', OrdinanceController::class)->only(['index']);
    Route::resource('projects', ProjectController::class)->only(['index']);
    Route::resource('employees', EmployeeController::class)->only(['index']);

    Route::get('missingPersons/comment/{missingPerson}',  [MissingPersonController::class, 'getCommentList']);
    Route::post('missingPersons/comment/{missingPerson}',  [MissingPersonController::class, 'comment']);
    Route::get('missingPersons/authReports', [MissingPersonController::class, 'authReports']);
    Route::get('missingPersons/', [MissingPersonController::class, 'index']);

    Route::get('missingItems/comment/{missingItem}',  [MissingItemController::class, 'getCommentList']);
    Route::post('missingItems/comment/{missingItem}',  [MissingItemController::class, 'comment']);
    Route::get('missingItems/authReports', [MissingItemController::class, 'authReports']);
    Route::get('missingItems/', [MissingItemController::class, 'index']);

    Route::get('complaints/getAnalytics', [ComplaintController::class, 'getAnalytics']);
    Route::get('complaints/', [ComplaintController::class, 'index']);
    Route::get('orders/', [OrderController::class, 'index']);
});

Route::group([
    'middleware' => 'jwtAuth:isVerified',
], function ($router) {
    Route::get('feedbacks/create', [FeedbackController::class, 'create']);
    Route::post('feedbacks/', [FeedbackController::class, 'store']);

    Route::get('reports/create', [ReportController::class, 'create']);
    Route::get('reports/{report}', [ReportController::class, 'show']);
    Route::post('reports/', [ReportController::class, 'store']);

    Route::resource('missingPersons', MissingPersonController::class)->except('index');
    Route::resource('missingItems', MissingItemController::class)->except('index');

    Route::resource('complaints', ComplaintController::class)->except('index');
    Route::resource('complainants', ComplainantController::class)->except(['index', 'create', 'show']);
    Route::resource('defendants', DefendantController::class)->except(['index', 'create', 'show']);

    Route::get('orders/create/{pickupType}', [OrderController::class, 'create']);
    Route::post('orders/submitReport/{order}', [OrderController::class, 'submitReport']);
    Route::get('orders/certificates', [OrderController::class, 'certificates']);
    Route::resource('orders', OrderController::class)->except(['index', 'create']);

    Route::get('bikers/latestVerification', [BikerController::class, 'latestVerification']);
    Route::post('bikers/postVerification', [BikerController::class, 'postVerification']);
});

Route::group([
    'middleware' => 'jwtAuth:isBiker',
], function ($router) {
    Route::get('bikers/getAuthAnalytics', [BikerController::class, 'getAuthAnalytics']);
    Route::get('bikers/getAuthTransaction', [BikerController::class, 'getAuthTransaction']);
    Route::get('bikers/getListOrders', [BikerController::class, 'getListOrders']);
    Route::get('bikers/getOrderDetails/{order}', [BikerController::class, 'getOrderDetails']);
    Route::put('bikers/bookedOrder/{order}', [BikerController::class, 'bookedOrder']);
    Route::put('bikers/startRiding/{order}', [BikerController::class, 'startRiding']);
    Route::put('bikers/confirmReceiveOrder/{order}', [BikerController::class, 'confirmReceiveOrder']);
    Route::put('bikers/confirmDNROrder/{order}', [BikerController::class, 'confirmDNROrder']);
});

    // Route::resource('feedbacks', FeedbackController::class)->except(['edit', 'update', 'show', 'delete']);
    // Route::get('reports/getAnalytics', [ReportController::class, 'getAnalytics']);
    // Route::resource('reports', ReportController::class)->except(['edit', 'update', 'delete']);

    // Route::get('announcements/like/{announcement}',  [AnnouncementController::class, 'getLikeList']);
    // Route::get('announcements/comment/{announcement}',  [AnnouncementController::class, 'getCommentList']);
    // Route::post('announcements/like/{announcement}',  [AnnouncementController::class, 'like']);
    // Route::post('announcements/comment/{announcement}',  [AnnouncementController::class, 'comment']);

    // Route::resource('announcements', AnnouncementController::class)->only(['index']);
    // Route::resource('comments', CommentController::class)->only(['edit', 'update', 'destroy']);

    // Route::resource('documents', DocumentController::class)->only(['index']);
    // Route::resource('ordinances', OrdinanceController::class)->only(['index']);
    // Route::resource('projects', ProjectController::class)->only(['index']);
    // Route::resource('employees', EmployeeController::class)->only(['index']);

    // Route::get('missingPersons/comment/{missingPerson}',  [MissingPersonController::class, 'getCommentList']);
    // Route::post('missingPersons/comment/{missingPerson}',  [MissingPersonController::class, 'comment']);
    // Route::get('missingPersons/authReports', [MissingPersonController::class, 'authReports']);
    // Route::resource('missingPersons', MissingPersonController::class);

    // Route::get('missingItems/comment/{missingItem}',  [MissingItemController::class, 'getCommentList']);
    // Route::post('missingItems/comment/{missingItem}',  [MissingItemController::class, 'comment']);
    // Route::get('missingItems/authReports', [MissingItemController::class, 'authReports']);
    // Route::resource('missingItems', MissingItemController::class);

    // Route::get('complaints/getAnalytics', [ComplaintController::class, 'getAnalytics']);
    // Route::resource('complaints', ComplaintController::class);
    // Route::resource('complainants', ComplainantController::class)->except(['index', 'create', 'show']);
    // Route::resource('defendants', DefendantController::class)->except(['index', 'create', 'show']);

    // Route::get('orders/create/{pickupType}', [OrderController::class, 'create']);
    // Route::post('orders/submitReport/{order}', [OrderController::class, 'submitReport']);
    // Route::get('orders/certificates', [OrderController::class, 'certificates']);
    // Route::resource('orders', OrderController::class)->except(['create']);

    // // bikers routes
    // Route::get('bikers/latestVerification', [BikerController::class, 'latestVerification']);
    // Route::post('bikers/postVerification', [BikerController::class, 'postVerification']);
    // Route::get('bikers/getAuthAnalytics', [BikerController::class, 'getAuthAnalytics']);
    // Route::get('bikers/getAuthTransaction', [BikerController::class, 'getAuthTransaction']);
    // Route::get('bikers/getListOrders', [BikerController::class, 'getListOrders']);
    // Route::get('bikers/getOrderDetails/{order}', [BikerController::class, 'getOrderDetails']);
    // Route::put('bikers/bookedOrder/{order}', [BikerController::class, 'bookedOrder']);
    // Route::put('bikers/startRiding/{order}', [BikerController::class, 'startRiding']);
    // Route::put('bikers/confirmReceiveOrder/{order}', [BikerController::class, 'confirmReceiveOrder']);
    // Route::put('bikers/confirmDNROrder/{order}', [BikerController::class, 'confirmDNROrder']);


// Route::resource('feedback-types', FeedbackTypeController::class)->except(['create']);
// Route::resource('document-types', DocumentTypeController::class)->except(['create']);
// Route::resource('terms', TermController::class)->except(['create']);
// Route::resource('positions', PositionController::class)->except(['create']);

// Route::resource('ordinance-types', OrdinanceTypeController::class)->except(['create']);
// Route::post('lost-and-found/{lost_and_found}/comment',  [LostAndFoundController::class, 'comment']);
// Route::put('lost-and-found/change-status/{lost_and_found}', [LostAndFoundController::class, 'changeStatus']);
// Route::resource('lost-and-found', LostAndFoundController::class);

// Route::resource('complaint-types', ComplaintTypeController::class)->except(['create']);
// // Route::put('complaints/change-status/{complaint}', [ComplaintController::class, 'changeStatus']);
// Route::resource('complaints', ComplaintController::class);
// // Route::resource('complainants', ComplainantController::class)->except(['index', 'create', 'show']);
// // Route::resource('defendants', DefendantController::class)->except(['index', 'create', 'show']);
// Route::resource('report-types', ReportTypeController::class)->except(['create']);

// // Route::put('reports/{report}/respond',  [ReportController::class, 'respond']);
// // Route::resource('reports', ReportController::class);
// Route::resource('announcement-types', AnnouncementTypeController::class)->except(['create']);

// Route::post('announcements/{announcement}/like',  [AnnouncementController::class, 'like']);
// // Route::post('announcements/{announcement}/comment',  [AnnouncementController::class, 'comment']);

// // Route::resource('announcement-pictures', AnnouncementPictureController::class)->only(['store', 'destroy']);
// // Route::resource('comments', CommentController::class)->only(['edit', 'update', 'destroy']);

// // Route::get('certificates/{certificate}/add-requirement',  [CertificateController::class, 'addRequirement']);
// // Route::post('certificates/store-requirements',  [CertificateController::class, 'storeRequirement']);
// // Route::delete('certificates/{certificate}/{requirement}',  [CertificateController::class, 'destroyRequirement']);


// Route::resource('requirements', RequirementController::class)->except(['create']);

// Route::resource('certificate-form-requests', CertificateFormController::class);


