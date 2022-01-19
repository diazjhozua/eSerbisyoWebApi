<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Web\Certification\ {
    CertificateController as CertificateCtrl,
    RequirementController as RequirementCtrl,
    BikerController as BikerCtrl,
    OrderController as OrderCtrl,
    CertificateFormController as CertificateFormCtrl
};

// For taskforce admin
Route::prefix('admin')->name('admin.')->middleware(['auth', 'isAdmin:certificateAdmin'])->group(function () {
    Route::get('certificates/report/{date_start}/{date_end}/{sort_column}/{sort_option}/{certificate_id}', [CertificateCtrl::class, 'report'])->name('certificates.report');
    Route::get('orders/report/{date_start}/{date_end}/{sort_column}/{sort_option}/{pick_up_type}/{order_status}/{application_status}', [OrderCtrl::class, 'report'])->name('orders.report');
});


// For certificate admin/staff
Route::prefix('admin')->name('admin.')->middleware(['auth', 'isAdmin:certificateStaff'])->group(function () {

    Route::get('certificates/{certificate}/add-requirement',  [CertificateCtrl::class, 'addRequirement']);
    Route::get('certificates/print/{certificateForm}',  [CertificateCtrl::class, 'printCertificate'])->name('certificates.printCertificate');
    Route::post('certificates/store-requirements',  [CertificateCtrl::class, 'storeRequirement'])->name('storeRequirement');
    Route::delete('certificates/{certificate}/{requirement}',  [CertificateCtrl::class, 'destroyRequirement']);

    Route::get('bikers/application-requests/', [BikerCtrl::class, 'applicationRequests'])->name('bikers.applicationRequests');
    Route::get('bikers/application-requests/{bikerRequest}', [BikerCtrl::class, 'getSingleApplication'])->name('bikers.getSingleApplication');
    Route::put('bikers/application-requests/{bikerRequest}', [BikerCtrl::class, 'verifyApplication'])->name('bikers.verifyApplication');

    Route::get('bikers/', [BikerCtrl::class, 'index'])->name('bikers.index');
    Route::get('bikers/{user}', [BikerCtrl::class, 'bikerProfile'])->name('bikers.profile');
    Route::put('bikers/demote/{user}', [BikerCtrl::class, 'demoteBiker'])->name('bikers.demoteBiker');

    Route::resource('certificates', CertificateCtrl::class)->except(['destroy', 'create', 'store']);
    Route::resource('requirements', RequirementCtrl::class)->except(['show']);

    Route::get('orders/receipt/{order}', [OrderCtrl::class, 'printReceipt'])->name('orders.receipt');
    Route::resource('orders', OrderCtrl::class);
    Route::resource('certificateForms', CertificateFormCtrl::class)->only(['edit', 'update']);

    Route::get('view-requirement/{fileName}', function ($fileName) {
        if(file_exists(Storage::disk('public')->path('requirements/'.$fileName))){
            $url = Storage::disk('public')->path('requirements/'.$fileName);
            return response()->file($url);
        }else{
            return view('errors.NOFILE');
        }
    })->name('viewRequirement');



});


