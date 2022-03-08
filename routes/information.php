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
    UserVerificationController as AdminUserVerification,
    StaffController as AdminStaff,
    AndroidController as AdminAndroid,
    InquiryController
};


// For taskforce admin
Route::prefix('admin')->name('admin.')->middleware(['auth', 'isAdmin:infoAdmin'])->group(function () {
    Route::get('feedback-types/report/{date_start}/{date_end}/{sort_column}/{sort_option}/', [AdminFeedbackType::class, 'report'])->name('feedback-types.report');
    // Route::post('feedback-types/report', [AdminFeedbackType::class, 'report'])->name('feedback-types.report'); //report type
    Route::get('feedback-types/report/{date_start}/{date_end}/{sort_column}/{sort_option}/{status_option}/{type_id}', [AdminFeedbackType::class, 'reportShow'])->name('feedback-types.report.show');
    // Route::post('feedback-types/report/{id}', [AdminFeedbackType::class, 'reportShow'])->name('feedback-types.report.show'); //report type show

    Route::get('feedbacks/report/{date_start}/{date_end}/{sort_column}/{sort_option}/{status_option}/', [AdminFeedback::class, 'report'])->name('feedbacks.report');
     Route::get('inquiries/report/{date_start}/{date_end}/{sort_column}/{sort_option}/{status_option}/', [InquiryController::class, 'report'])->name('inquiries.report');
    // Route::post('feedbacks/report', [AdminFeedback::class, 'report'])->name('feedbacks.report');

    Route::get('document-types/report/{date_start}/{date_end}/{sort_column}/{sort_option}/', [AdminDocumentType::class, 'report'])->name('document-types.report');
    // Route::post('document-types/report', [AdminDocumentType::class, 'report'])->name('document-types.report');
    Route::get('document-types/report/{date_start}/{date_end}/{sort_column}/{sort_option}/{type_id}', [AdminDocumentType::class, 'reportShow'])->name('document-types.report.show');
    // Route::post('document-types/report/{id}', [AdminDocumentType::class, 'reportShow'])->name('document-types.report.show');

    // Route::post('documents/report', [AdminDocument::class, 'report'])->name('documents.report');
    Route::get('documents/report/{date_start}/{date_end}/{sort_column}/{sort_option}/', [AdminDocument::class, 'report'])->name('documents.report');

    Route::get('ordinance-types/report/{date_start}/{date_end}/{sort_column}/{sort_option}/', [AdminOrdinanceType::class, 'report'])->name('ordinance-types.report');
    // Route::post('ordinance-types/report', [AdminOrdinanceType::class, 'report'])->name('ordinance-types.report');
    Route::get('ordinance-types/report/{date_start}/{date_end}/{sort_column}/{sort_option}/{type_id}', [AdminOrdinanceType::class, 'reportShow'])->name('ordinance-types.report.show');
    // Route::post('ordinance-types/report/{id}', [AdminOrdinanceType::class, 'reportShow'])->name('ordinance-types.report.show');

    // Route::post('ordinances/report', [AdminOrdinance::class, 'report'])->name('ordinances.report');
    Route::get('ordinances/report/{date_start}/{date_end}/{sort_column}/{sort_option}/', [AdminOrdinance::class, 'report'])->name('ordinances.report');

    Route::get('project-types/report/{date_start}/{date_end}/{sort_column}/{sort_option}/', [AdminProjectType::class, 'report'])->name('project-types.report');
    // Route::post('project-types/report', [AdminProjectType::class, 'report'])->name('project-types.report');
    Route::get('project-types/report/{date_start}/{date_end}/{sort_column}/{sort_option}/{type_id}', [AdminProjectType::class, 'reportShow'])->name('project-types.report.show');
    // Route::post('project-types/report/{id}', [AdminProjectType::class, 'reportShow'])->name('project-types.report.show');

    // Route::post('projects/report', [AdminProject::class, 'report'])->name('projects.report');
    Route::get('projects/report/{date_start}/{date_end}/{sort_column}/{sort_option}/', [AdminProject::class, 'report'])->name('projects.report');

    Route::get('announcement-types/report/{date_start}/{date_end}/{sort_column}/{sort_option}/', [AdminAnnouncementType::class, 'report'])->name('announcement-types.report');
    // Route::post('announcement-types/report', [AdminAnnouncementType::class, 'report'])->name('announcement-types.report');
    Route::get('announcement-types/report/{date_start}/{date_end}/{sort_column}/{sort_option}/{type_id}', [AdminAnnouncementType::class, 'reportShow'])->name('announcement-types.report.show');
    // Route::post('announcement-types/report/{id}', [AdminAnnouncementType::class, 'reportShow'])->name('announcement-types.report.show');

    // Route::post('announcements/report', [AdminAnnouncement::class, 'report'])->name('announcements.report');
    Route::get('announcements/report/{date_start}/{date_end}/{sort_column}/{sort_option}/', [AdminAnnouncement::class, 'report'])->name('announcements.report');

    Route::get('announcements/report/{announcement}', [AdminAnnouncement::class, 'reportProfile'])->name('announcements.reportProfile');

    Route::get('users/report/{date_start}/{date_end}/{filter}/{sort_column}/{sort_option}/', [AdminUser::class, 'report'])->name('users.report');
    // Route::post('users/report', [AdminUser::class, 'report'])->name('users.report');

});


// For Super Admin, Info Admin, and Info Staff
Route::prefix('admin')->name('admin.')->middleware(['auth', 'isAdmin:infoStaff'])->group(function () {

    Route::resource('feedback-types', AdminFeedbackType::class)->except(['create']); //resource except create method
    Route::put('feedbacks/respond/{feedback}', [AdminFeedback::class, 'respondReport']);
    Route::get('feedbacks', [AdminFeedback::class, 'index'])->name('feedbacks.index');

    Route::put('inquiries/respond/{inquiry}', [InquiryController::class, 'respondReport']);
    Route::get('inquiries', [InquiryController::class, 'index'])->name('inquiries.index');

    Route::resource('document-types', AdminDocumentType::class)->except(['create']);
    Route::resource('documents', AdminDocument::class)->except(['show']);

    Route::resource('ordinance-types', AdminOrdinanceType::class)->except(['create']);
    Route::resource('ordinances', AdminOrdinance::class)->except(['show']);

    Route::resource('project-types', AdminProjectType::class)->except(['create']);
    Route::resource('projects', AdminProject::class)->except(['show']);

    Route::resource('androids', AdminAndroid::class)->except(['create', 'show']);
    Route::resource('terms', AdminTerm::class)->except(['create']);
    Route::resource('positions', AdminPosition::class)->except(['create']);
    Route::resource('employees', AdminEmployee::class)->except(['show']);

    Route::resource('announcement-types', AdminAnnouncementType::class)->except(['create']);
    Route::resource('announcement-pictures', AdminAnnouncementPicture::class)->except(['index', 'show', 'create']);
    Route::resource('announcements', AdminAnnouncement::class);

    Route::get('user-verifications', [AdminUser::class, 'index'])->name('users.index');

    Route::resource('user-verifications', AdminUserVerification::class)->except(['create', 'store', 'show', 'destroy']);


});


