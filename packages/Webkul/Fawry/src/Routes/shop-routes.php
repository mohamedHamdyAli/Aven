<?php

use Illuminate\Support\Facades\Route;
use Webkul\Fawry\Http\Controllers\Shop\FawryController;

Route::group(['middleware' => ['web', 'locale', 'theme', 'currency']], function () {
    Route::get('/fawry/redirect', [FawryController::class, 'redirect'])->name('fawry.redirect');
    Route::get('/fawry/callback', [FawryController::class, 'callback'])->name('fawry.callback');
});
