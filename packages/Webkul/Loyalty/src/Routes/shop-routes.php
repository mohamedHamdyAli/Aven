<?php

use Illuminate\Support\Facades\Route;
use Webkul\Loyalty\Http\Controllers\Shop\LoyaltyController;

Route::middleware(['web', 'customer'])->group(function () {
    Route::post('/loyalty/apply', [LoyaltyController::class, 'apply'])->name('shop.loyalty.apply');
    Route::post('/loyalty/remove', [LoyaltyController::class, 'remove'])->name('shop.loyalty.remove');
});
