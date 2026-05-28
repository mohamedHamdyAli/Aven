<?php

use Illuminate\Support\Facades\Route;
use Webkul\OrderNotification\Http\Controllers\OrderTrackingController;

Route::group(['middleware' => ['web', 'locale', 'theme', 'currency']], function () {
    Route::prefix('track-order')->name('shop.order.track.')->group(function () {
        Route::get('/', [OrderTrackingController::class, 'index'])->name('index');
        Route::post('/', [OrderTrackingController::class, 'track'])->name('result');
    });
});
