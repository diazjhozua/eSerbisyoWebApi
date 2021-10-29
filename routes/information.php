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
};

// For Super Admin, Info Admin, and Info Staff
Route::prefix('admin')->name('admin.')->middleware(['auth', 'isAdmin:infoStaff'])->group(function () {
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

    Route::get('users', [AdminUser::class, 'index'])->name('users.index');
    Route::put('users/changeStatus/{user}', [AdminUser::class, 'changeUserStatus']);
    Route::get('users/viewUserVerification/{user_verification}', [AdminUser::class, 'viewUserVerification']);
    Route::put('users/verifyUser/{user_verification}', [AdminUser::class, 'verifyUser']);
});

// For Super Admin and Info Admin
Route::prefix('admin')->name('admin.')->middleware(['auth', 'isAdmin:infoAdmin'])->group(function () {
    Route::get('staffs', [AdminStaff::class, 'adminStaff'])->name('staffs.adminStaff');
    Route::put('staffs/demoteStaff/{user}', [AdminStaff::class, 'demoteStaff'])->name('staffs.demoteStaff');
    Route::get('staffs/promote-users', [AdminStaff::class, 'users'])->name('staffs.users');
    Route::put('staffs/promote-users/{user}', [AdminStaff::class, 'promoteUser'])->name('staffs.promoteUser');
});

