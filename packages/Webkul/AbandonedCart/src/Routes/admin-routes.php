<?php

use Illuminate\Support\Facades\Route;
use Webkul\AbandonedCart\Http\Controllers\Admin\AbandonedCartController;

Route::group([
    'prefix'     => 'admin/abandoned-carts',
    'middleware' => ['web', 'admin'],
], function () {
    Route::get('/', [AbandonedCartController::class, 'index'])
        ->name('admin.sales.abandoned-carts.index');

    Route::post('{cartId}/send-now', [AbandonedCartController::class, 'sendNow'])
        ->name('admin.sales.abandoned-carts.send-now');

    Route::patch('{cartId}/recover', [AbandonedCartController::class, 'markRecovered'])
        ->name('admin.sales.abandoned-carts.mark-recovered');

    Route::patch('{cartId}/expire', [AbandonedCartController::class, 'expire'])
        ->name('admin.sales.abandoned-carts.expire');
});
