<?php

use Illuminate\Support\Facades\Route;
use Webkul\Paymob\Http\Controllers\PaymobController;

Route::group(['middleware' => ['web', 'locale', 'theme', 'currency']], function () {
    Route::get('/paymob/redirect',  [PaymobController::class, 'redirect'])->name('paymob.redirect');
    Route::get('/paymob/callback',  [PaymobController::class, 'callback'])->name('paymob.callback');
});
