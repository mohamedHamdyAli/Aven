<?php

use Illuminate\Support\Facades\Route;
use Webkul\AbandonedCart\Http\Controllers\Shop\RecoveryController;

Route::group(['middleware' => ['web']], function () {
    Route::get('cart/recover/{token}', [RecoveryController::class, 'recover'])
        ->name('shop.abandoned-cart.recover')
        ->middleware('signed');

    Route::get('cart/unsubscribe/{token}', [RecoveryController::class, 'unsubscribe'])
        ->name('shop.abandoned-cart.unsubscribe');
});
