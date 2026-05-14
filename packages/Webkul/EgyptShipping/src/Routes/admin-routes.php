<?php

use Illuminate\Support\Facades\Route;
use Webkul\EgyptShipping\Http\Controllers\Admin\EgyptShippingController;

Route::group([
    'prefix'     => config('app.admin_url', 'admin'),
    'middleware' => ['web', 'admin'],
], function () {
    Route::get('egypt-shipping', [EgyptShippingController::class, 'index'])
        ->name('admin.egypt-shipping.index');

    Route::put('egypt-shipping/{id}', [EgyptShippingController::class, 'update'])
        ->name('admin.egypt-shipping.update');

    Route::post('egypt-shipping/bulk-update', [EgyptShippingController::class, 'bulkUpdate'])
        ->name('admin.egypt-shipping.bulk-update');
});
