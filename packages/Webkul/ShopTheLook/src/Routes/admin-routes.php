<?php

use Illuminate\Support\Facades\Route;
use Webkul\ShopTheLook\Http\Controllers\Admin\ShopTheLookController;

Route::group(['middleware' => ['admin'], 'prefix' => config('app.admin_url')], function () {
    Route::controller(ShopTheLookController::class)->prefix('catalog/products/look')->group(function () {
        Route::get('{productId}/items',  'items')->name('admin.shop-the-look.items');
        Route::get('{productId}/search', 'search')->name('admin.shop-the-look.search');
        Route::post('{productId}/sync',  'sync')->name('admin.shop-the-look.sync');
    });
});
