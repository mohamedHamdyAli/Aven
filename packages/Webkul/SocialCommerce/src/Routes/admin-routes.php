<?php

use Illuminate\Support\Facades\Route;
use Webkul\SocialCommerce\Http\Controllers\Admin\SocialChannelController;

Route::middleware(['web', 'admin'])->group(function () {
    Route::prefix('admin/social-commerce')->name('admin.social-commerce.')->group(function () {
        Route::controller(SocialChannelController::class)
            ->prefix('channels')
            ->name('channels.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{id}/edit', 'edit')->name('edit');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('destroy');
                Route::get('/{id}/sync', 'sync')->name('sync');
            });
    });
});
