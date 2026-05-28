<?php

use Illuminate\Support\Facades\Route;
use Webkul\Bosta\Http\Controllers\Admin\BostaController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => config('app.admin_url', 'admin')], function () {
    Route::controller(BostaController::class)->prefix('bosta')->group(function () {
        Route::get('', 'index')->name('admin.bosta.index');
        Route::post('orders/{id}/create-shipment', 'create')->name('admin.bosta.create');
        Route::get('track/{tracking}', 'track')->name('admin.bosta.track');
    });
});
