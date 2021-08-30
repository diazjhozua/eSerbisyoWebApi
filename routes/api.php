<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\DocumentTypeController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\APi\OrdinanceCategoryController;
use App\Http\Controllers\Api\TermController;
use App\Http\Controllers\Api\PositionController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\MissingPersonController;


use App\Http\Controllers\Api\LostAndFoundController;
use App\Models\LostAndFound;

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

Route::resource('ordinance-categories', OrdinanceCategoryController::class);

Route::resource('terms', TermController::class)->except(['create']);
Route::resource('positions', PositionController::class)->except(['create']);
Route::resource('employees', EmployeeController::class);
Route::resource('missing-persons', MissingPersonController::class);
Route::resource('lost-and-found', LostAndFoundController::class);




