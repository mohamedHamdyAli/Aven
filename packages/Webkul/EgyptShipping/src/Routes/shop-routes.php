<?php

use Illuminate\Support\Facades\Route;
use Webkul\EgyptShipping\Http\Controllers\Shop\OrderTrackingController;

Route::group(['middleware' => ['web']], function () {
    Route::get('track-order', [OrderTrackingController::class, 'index'])
        ->name('egypt-shipping.track-order.index');

    Route::post('track-order', [OrderTrackingController::class, 'show'])
        ->name('egypt-shipping.track-order.show');
});
