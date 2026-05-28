<?php

use Illuminate\Support\Facades\Route;
use Webkul\Aramex\Http\Controllers\Admin\AramexController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => config('app.admin_url', 'admin')], function () {
    Route::controller(AramexController::class)->prefix('aramex')->group(function () {
        Route::get('', 'index')->name('admin.aramex.index');
        Route::post('orders/{id}/create-shipment', 'create')->name('admin.aramex.create');
        Route::get('track/{waybill}', 'track')->name('admin.aramex.track');
    });
});
