<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\DocumentTypeController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\APi\OrdinanceCategoryController;
use App\Http\Controllers\APi\OrdinanceController;
use App\Http\Controllers\Api\TermController;
use App\Http\Controllers\Api\PositionController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\MissingPersonController;
use App\Http\Controllers\Api\LostAndFoundController;
use App\Http\Controllers\Api\ComplaintTypeController;
use App\Http\Controllers\Api\ComplaintController;
use App\Http\Controllers\Api\ComplainantController;


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

Route::resource('feedbacks', FeedbackController::class)->only(['index', 'store']);
Route::resource('document-types', DocumentTypeController::class)->except(['create']);
Route::resource('documents', DocumentController::class)->except(['show']);

Route::resource('ordinance-categories', OrdinanceCategoryController::class)->except(['create']);
Route::resource('ordinances', OrdinanceController::class)->except(['show']);

Route::resource('terms', TermController::class)->except(['create']);
Route::resource('positions', PositionController::class)->except(['create']);
Route::resource('employees', EmployeeController::class);

Route::put('missing-persons/change-status/{id}', [MissingPersonController::class, 'changeStatus']);
Route::resource('missing-persons', MissingPersonController::class);

Route::put('lost-and-found/change-status/{id}', [LostAndFoundController::class, 'changeStatus']);
Route::resource('lost-and-found', LostAndFoundController::class);

Route::resource('complaint-types', ComplaintTypeController::class);

Route::put('complaints/change-status/{id}', [ComplaintController::class, 'changeStatus']);
Route::resource('complaints', ComplaintController::class);

Route::resource('complainant-lists', ComplainantController::class);
Route::resource('defendant-lists', DefendantController::class);




