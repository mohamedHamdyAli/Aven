<?php

use Illuminate\Support\Facades\Route;
use Webkul\Affiliate\Http\Controllers\Admin\AffiliateController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => config('app.admin_path', 'admin')], function () {
    Route::prefix('affiliates')->name('admin.affiliates.')->group(function () {
        Route::get('/',                         [AffiliateController::class, 'index'])->name('index');
        Route::get('/{id}',                     [AffiliateController::class, 'show'])->name('show');
        Route::post('/{id}/approve',            [AffiliateController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject',             [AffiliateController::class, 'reject'])->name('reject');
        Route::post('/mark-paid',               [AffiliateController::class, 'markPaid'])->name('mark-paid');
        Route::post('/commission/{id}/approve', [AffiliateController::class, 'approveCommission'])->name('commission.approve');
    });
});
