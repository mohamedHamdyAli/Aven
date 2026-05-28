<?php

use Illuminate\Support\Facades\Route;
use Webkul\PushNotification\Http\Controllers\Shop\PushSubscriptionController;

Route::group(['middleware' => ['web', 'locale', 'theme', 'currency']], function () {
    Route::prefix('push')->name('shop.push.')->group(function () {
        Route::get('/vapid-key',   [PushSubscriptionController::class, 'vapidKey'])->name('vapid-key');
        Route::post('/subscribe',  [PushSubscriptionController::class, 'subscribe'])->name('subscribe');
        Route::post('/unsubscribe',[PushSubscriptionController::class, 'unsubscribe'])->name('unsubscribe');
    });
});
