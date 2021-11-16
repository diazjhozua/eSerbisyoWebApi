<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ {
    FeedbackTypeController,
    FeedbackController,
    DocumentTypeController,
    DocumentController,
    TermController,
    PositionController,
    EmployeeController,
    OrdinanceTypeController,
    OrdinanceController,
    MissingPersonController,
    LostAndFoundController,
    ComplaintTypeController,
    ComplaintController,
    ComplainantController,
    DefendantController,
    ReportTypeController,
    ReportController,
    AnnouncementTypeController,
    AnnouncementController,
    AnnouncementPictureController,
    ProjectController,
    CommentController,
    CertificateController,
    RequirementController,
    CertificateFormController,

};
use App\Http\Requests\CertificateFormRequest;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('feedback-types', FeedbackTypeController::class)->except(['create']);
Route::put('feedbacks/{feedback}/noted', [FeedbackController::class, 'noted']);
Route::resource('feedbacks', FeedbackController::class)->except(['edit', 'update', 'delete']);
Route::resource('document-types', DocumentTypeController::class)->except(['create']);
Route::resource('documents', DocumentController::class);

Route::resource('terms', TermController::class)->except(['create']);
Route::resource('positions', PositionController::class)->except(['create']);
Route::resource('employees', EmployeeController::class);
Route::resource('projects', ProjectController::class);

Route::resource('ordinance-types', OrdinanceTypeController::class)->except(['create']);
Route::resource('ordinances', OrdinanceController::class);

Route::post('missing-persons/{missing_person}/comment',  [MissingPersonController::class, 'comment']);
Route::put('missing-persons/change-status/{missing_person}', [MissingPersonController::class, 'changeStatus']);
Route::resource('missing-persons', MissingPersonController::class);
Route::post('lost-and-found/{lost_and_found}/comment',  [LostAndFoundController::class, 'comment']);
Route::put('lost-and-found/change-status/{lost_and_found}', [LostAndFoundController::class, 'changeStatus']);
Route::resource('lost-and-found', LostAndFoundController::class);

Route::resource('complaint-types', ComplaintTypeController::class)->except(['create']);
Route::put('complaints/change-status/{complaint}', [ComplaintController::class, 'changeStatus']);
Route::resource('complainants', ComplainantController::class)->except(['index', 'create', 'show']);
Route::resource('defendants', DefendantController::class)->except(['index', 'create', 'show']);
Route::resource('report-types', ReportTypeController::class)->except(['create']);

Route::put('reports/{report}/respond',  [ReportController::class, 'respond']);
Route::resource('reports', ReportController::class);
Route::resource('announcement-types', AnnouncementTypeController::class)->except(['create']);

Route::post('announcements/{announcement}/like',  [AnnouncementController::class, 'like']);
// Route::post('announcements/{announcement}/comment',  [AnnouncementController::class, 'comment']);
Route::resource('announcements', AnnouncementController::class);
// Route::resource('announcement-pictures', AnnouncementPictureController::class)->only(['store', 'destroy']);
// Route::resource('comments', CommentController::class)->only(['edit', 'update', 'destroy']);

// Route::get('certificates/{certificate}/add-requirement',  [CertificateController::class, 'addRequirement']);
// Route::post('certificates/store-requirements',  [CertificateController::class, 'storeRequirement']);
// Route::delete('certificates/{certificate}/{requirement}',  [CertificateController::class, 'destroyRequirement']);


Route::resource('certificates', CertificateController::class)->except(['create', 'store', 'destroy']);
Route::resource('requirements', RequirementController::class)->except(['create']);

Route::resource('certificate-form-requests', CertificateFormController::class);






