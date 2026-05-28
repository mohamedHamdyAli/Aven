<?php

use Illuminate\Support\Facades\Route;
use Webkul\Referral\Http\Controllers\Shop\ReferralController;

Route::middleware('web')->group(function () {
    Route::get('/ref/{code}', [ReferralController::class, 'track'])->name('shop.referral.track');
});

Route::middleware(['web', 'customer'])->group(function () {
    Route::get('/referral/dashboard', [ReferralController::class, 'dashboard'])->name('shop.referral.dashboard');
});
