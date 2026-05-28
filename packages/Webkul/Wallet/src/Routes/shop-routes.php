<?php

use Illuminate\Support\Facades\Route;
use Webkul\Wallet\Http\Controllers\Shop\WalletController;

Route::group(['middleware' => ['web', 'locale', 'theme', 'currency', 'customer']], function () {
    Route::prefix('wallet')->name('shop.wallet.')->group(function () {
        Route::get('/',        [WalletController::class, 'index'])->name('index');
        Route::post('/apply',  [WalletController::class, 'apply'])->name('apply');
        Route::post('/remove', [WalletController::class, 'remove'])->name('remove');
    });
});
