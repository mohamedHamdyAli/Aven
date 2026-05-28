<?php

use Illuminate\Support\Facades\Route;
use Webkul\GiftCard\Http\Controllers\Shop\GiftCardController;

Route::group(['middleware' => ['web', 'locale', 'theme', 'currency']], function () {
    Route::post('/gift-card/apply', [GiftCardController::class, 'apply'])->name('shop.gift-card.apply');
    Route::post('/gift-card/remove', [GiftCardController::class, 'remove'])->name('shop.gift-card.remove');
});
