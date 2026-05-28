<?php

use Illuminate\Support\Facades\Route;
use Webkul\GoogleShopping\Http\Controllers\FeedController;

Route::group(['middleware' => ['web', 'locale', 'theme', 'currency']], function () {
    Route::get('/google-shopping.xml', [FeedController::class, 'feed'])->name('shop.google-shopping.feed');
});
