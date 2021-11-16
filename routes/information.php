<?php

use Illuminate\Support\Facades\Route;
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
    StaffController as AdminStaff,
    AndroidController as AdminAndroid
};

// For Super Admin, Info Admin, and Info Staff
Route::prefix('admin')->name('admin.')->middleware(['auth', 'isAdmin:infoStaff'])->group(function () {

    Route::post('feedback-types/report', [AdminFeedbackType::class, 'report'])->name('feedback-types.report'); //report type
    Route::post('feedback-types/report/{id}', [AdminFeedbackType::class, 'reportShow'])->name('feedback-types.report.show'); //report type show
    Route::resource('feedback-types', AdminFeedbackType::class)->except(['create']); //resource except create method

    Route::put('feedbacks/respond/{feedback}', [AdminFeedback::class, 'respondReport']);
    Route::post('feedbacks/report', [AdminFeedback::class, 'report'])->name('feedbacks.report');
    Route::get('feedbacks', [AdminFeedback::class, 'index'])->name('feedbacks.index');

    Route::post('document-types/report', [AdminDocumentType::class, 'report'])->name('document-types.report');
    Route::post('document-types/report/{id}', [AdminDocumentType::class, 'reportShow'])->name('document-types.report.show');
    Route::resource('document-types', AdminDocumentType::class)->except(['create']);
    Route::post('documents/report', [AdminDocument::class, 'report'])->name('documents.report');
    Route::resource('documents', AdminDocument::class)->except(['show']);

    Route::post('ordinance-types/report', [AdminOrdinanceType::class, 'report'])->name('ordinance-types.report');
    Route::post('ordinance-types/report/{id}', [AdminOrdinanceType::class, 'reportShow'])->name('ordinance-types.report.show');
    Route::resource('ordinance-types', AdminOrdinanceType::class)->except(['create']);
    Route::post('ordinances/report', [AdminOrdinance::class, 'report'])->name('ordinances.report');
    Route::resource('ordinances', AdminOrdinance::class)->except(['show']);

    Route::post('project-types/report', [AdminProjectType::class, 'report'])->name('project-types.report');
    Route::post('project-types/report/{id}', [AdminProjectType::class, 'reportShow'])->name('project-types.report.show');
    Route::resource('project-types', AdminProjectType::class)->except(['create']);
    Route::post('projects/report', [AdminProject::class, 'report'])->name('projects.report');
    Route::resource('projects', AdminProject::class)->except(['show']);

    Route::resource('androids', AdminAndroid::class)->except(['create', 'show']);
    Route::resource('terms', AdminTerm::class)->except(['create']);
    Route::resource('positions', AdminPosition::class)->except(['create']);
    Route::resource('employees', AdminEmployee::class)->except(['show']);

    Route::post('announcement-types/report', [AdminAnnouncementType::class, 'report'])->name('announcement-types.report');
    Route::post('announcement-types/report/{id}', [AdminAnnouncementType::class, 'reportShow'])->name('announcement-types.report.show');
    Route::resource('announcement-types', AdminAnnouncementType::class)->except(['create']);
    Route::resource('announcement-pictures', AdminAnnouncementPicture::class)->except(['index', 'show', 'create']);


    Route::post('announcements/report', [AdminAnnouncement::class, 'report'])->name('announcements.report');
    Route::get('announcements/report/{announcement}', [AdminAnnouncement::class, 'reportProfile'])->name('announcements.reportProfile');
    Route::resource('announcements', AdminAnnouncement::class);

    Route::post('users/report', [AdminUser::class, 'report'])->name('users.report');
    Route::get('users', [AdminUser::class, 'index'])->name('users.index');
    Route::put('users/changeStatus/{user}', [AdminUser::class, 'changeUserStatus']);
    Route::get('users/viewUserVerification/{user_verification}', [AdminUser::class, 'viewUserVerification']);
    Route::put('users/verifyUser/{user_verification}', [AdminUser::class, 'verifyUser']);
});


