<?php

use Illuminate\Support\Facades\Route;
use Webkul\FlashSale\Http\Controllers\Admin\FlashSaleController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => config('app.admin_url', 'admin')], function () {
    Route::controller(FlashSaleController::class)->prefix('marketing/flash-sales')->group(function () {
        Route::get('', 'index')->name('admin.marketing.flash-sales.index');
        Route::get('create', 'create')->name('admin.marketing.flash-sales.create');
        Route::post('', 'store')->name('admin.marketing.flash-sales.store');
        Route::delete('{id}', 'destroy')->name('admin.marketing.flash-sales.destroy');
    });
});
