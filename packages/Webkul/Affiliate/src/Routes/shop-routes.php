<?php

use Illuminate\Support\Facades\Route;
use Webkul\Affiliate\Http\Controllers\Shop\AffiliateController;

Route::group(['middleware' => ['web', 'locale', 'theme', 'currency']], function () {
    Route::get('/ref/{code}', [AffiliateController::class, 'track'])->name('shop.affiliate.track');
});

Route::group(['middleware' => ['web', 'locale', 'theme', 'currency', 'customer']], function () {
    Route::prefix('affiliate')->name('shop.affiliate.')->group(function () {
        Route::get('/register',  [AffiliateController::class, 'register'])->name('register');
        Route::post('/apply',    [AffiliateController::class, 'apply'])->name('apply');
        Route::get('/dashboard', [AffiliateController::class, 'dashboard'])->name('dashboard');
    });
});
