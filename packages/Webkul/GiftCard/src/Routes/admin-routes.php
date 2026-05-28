<?php

use Illuminate\Support\Facades\Route;
use Webkul\GiftCard\Http\Controllers\Admin\GiftCardController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => config('app.admin_url', 'admin')], function () {
    Route::controller(GiftCardController::class)->prefix('gift-cards')->group(function () {
        Route::get('', 'index')->name('admin.gift-cards.index');
        Route::get('create', 'create')->name('admin.gift-cards.create');
        Route::post('', 'store')->name('admin.gift-cards.store');
        Route::delete('{id}', 'destroy')->name('admin.gift-cards.destroy');
    });
});
