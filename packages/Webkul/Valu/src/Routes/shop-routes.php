<?php

use Illuminate\Support\Facades\Route;
use Webkul\Valu\Http\Controllers\Shop\ValuController;

Route::middleware(['web'])->group(function () {
    Route::get('/valu/redirect', [ValuController::class, 'redirect'])->name('valu.redirect');
    Route::get('/valu/callback', [ValuController::class, 'callback'])->name('valu.callback');
});
