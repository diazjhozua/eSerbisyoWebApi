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
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

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
Route::resource('ordinance-types', OrdinanceTypeController::class)->except(['create']);
Route::resource('ordinances', OrdinanceController::class);
Route::put('missing-persons/change-status/{missing_person}', [MissingPersonController::class, 'changeStatus']);
Route::resource('missing-persons', MissingPersonController::class);
Route::put('lost-and-found/change-status/{lost_and_found}', [LostAndFoundController::class, 'changeStatus']);
Route::resource('lost-and-found', LostAndFoundController::class);
Route::resource('complaint-types', ComplaintTypeController::class)->except(['create']);
Route::resource('complaints', ComplaintController::class);


// Route::put('complaints/change-status/{id}', [ComplaintController::class, 'changeStatus']);


// Route::put('complainants', [ComplainantController::class, 'update']);
// Route::resource('complainants', ComplainantController::class)->except(['index', 'create', 'show', 'update']);

// Route::put('defendants', [DefendantController::class, 'update']);
// Route::resource('defendants', DefendantController::class)->except(['index', 'create', 'show', 'update']);

// Route::resource('report-types', ReportTypeController::class)->except(['create']);

// Route::put('reports/respond', [ReportController::class, 'respond']);
// Route::resource('reports', ReportController::class);

// Route::resource('announcement-types', AnnouncementTypeController::class)->except(['create']);
// Route::resource('announcements', AnnouncementController::class);




