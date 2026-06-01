<?php

use Illuminate\Support\Facades\Route;
use Webkul\EgyptShipping\Http\Controllers\Shop\OrderTrackingController;

Route::group(['middleware' => ['web']], function () {
    Route::get('track-order', [OrderTrackingController::class, 'index'])
        ->name('egypt-shipping.track-order.index');

    Route::post('track-order', [OrderTrackingController::class, 'show'])
        ->name('egypt-shipping.track-order.show');
});

Route::group(['middleware' => ['api'], 'prefix' => 'api'], function () {
    Route::get('egypt-shipping/rate/{code}', [\Webkul\EgyptShipping\Http\Controllers\Shop\RateController::class, 'show'])
        ->name('egypt-shipping.api.rate');
});
