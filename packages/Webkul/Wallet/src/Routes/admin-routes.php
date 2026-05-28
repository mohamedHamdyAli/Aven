<?php

use Illuminate\Support\Facades\Route;
use Webkul\Wallet\Http\Controllers\Admin\WalletController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => config('app.admin_path', 'admin')], function () {
    Route::prefix('wallet')->name('admin.wallet.')->group(function () {
        Route::get('/',                       [WalletController::class, 'index'])->name('index');
        Route::get('/customer/{id}',          [WalletController::class, 'customer'])->name('customer');
        Route::post('/issue',                 [WalletController::class, 'issue'])->name('issue');
        Route::post('/revoke',                [WalletController::class, 'revoke'])->name('revoke');
    });
});
