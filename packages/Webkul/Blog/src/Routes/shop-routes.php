<?php

use Illuminate\Support\Facades\Route;
use Webkul\Blog\Http\Controllers\Shop\BlogController;

Route::group(['middleware' => ['web', 'locale', 'theme', 'currency']], function () {
    Route::prefix('blog')->name('shop.blog.')->group(function () {
        Route::get('/',          [BlogController::class, 'index'])->name('index');
        Route::get('/{slug}',    [BlogController::class, 'show'])->name('show');
    });
});
