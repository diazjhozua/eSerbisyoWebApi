<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Web\InformationPageController;

use App\Http\Controllers\Web\Admin\ {
    FeedbackController as AdminFeedback,
    FeedbackTypeController as AdminFeedbackType,
    DocumentTypeController as AdminDocumentType,
    DocumentController as AdminDocument,
    OrdinanceTypeController as AdminOrdinanceType,
    OrdinanceController as AdminOrdinance,
    ProjectTypeController as AdminProjectType,
    ProjectController as AdminProject,
    TermController as AdminTerm,
    PositionController as AdminPosition,
    EmployeeController as AdminEmployee,
    AnnouncementTypeController as AdminAnnouncementType,
    AnnouncementController as AdminAnnouncement,
    AnnouncementPictureController as AdminAnnouncementPicture,
    UserController as AdminUser,
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

Route::get('/', function () {
    return view('dashboards.information');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('feedback-types', AdminFeedbackType::class)->except(['create']);
    Route::put('feedbacks/respond/{feedback}', [AdminFeedback::class, 'respondReport']);
    Route::get('feedbacks', [AdminFeedback::class, 'index'])->name('feedbacks.index');;
    Route::resource('document-types', AdminDocumentType::class)->except(['create']);
    Route::resource('documents', AdminDocument::class)->except(['show']);
    Route::resource('ordinance-types', AdminOrdinanceType::class)->except(['create']);
    Route::resource('ordinances', AdminOrdinance::class)->except(['show']);
    Route::resource('project-types', AdminProjectType::class)->except(['create']);
    Route::resource('projects', AdminProject::class)->except(['show']);
    Route::resource('terms', AdminTerm::class)->except(['create']);
    Route::resource('positions', AdminPosition::class)->except(['create']);
    Route::resource('employees', AdminEmployee::class)->except(['show']);
    Route::resource('announcement-types', AdminAnnouncementType::class)->except(['create']);
    Route::resource('announcement-pictures', AdminAnnouncementPicture::class)->except(['index', 'show', 'create']);
    Route::resource('announcements', AdminAnnouncement::class);



    Route::get('files/{folderName}/{fileName}', function ($folderName, $fileName) {
        $url = Storage::disk('public')->path($folderName.'/'.$fileName);
        return response()->file($url);
    })->name('viewFiles');

});


Route::get('/admin/information/dashboard', [InformationPageController::class, 'informationDashboard'])->name('information-dashboard');
Route::get('/admin/information/missing-report', [InformationPageController::class, 'missingReport'])->name('missing-report');



