<?php

use Illuminate\Support\Facades\Route;
use Webkul\SizeGuide\Http\Controllers\Shop\SizeGuideController;

Route::group(['middleware' => ['web']], function () {
    Route::get('size-guide/product/{productId}', [SizeGuideController::class, 'show'])
        ->name('shop.size-guide.show');

    Route::get('size-guide/product/{productId}/page', [SizeGuideController::class, 'page'])
        ->name('shop.size-guide.page');

    Route::post('size-guide/product/{productId}/recommend', [SizeGuideController::class, 'recommend'])
        ->name('shop.size-guide.recommend');
});
