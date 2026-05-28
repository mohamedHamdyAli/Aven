<?php

use Illuminate\Support\Facades\Route;
use Webkul\PushNotification\Http\Controllers\Admin\PushCampaignController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => config('app.admin_path', 'admin')], function () {
    Route::prefix('push-notifications')->name('admin.push.')->group(function () {
        Route::get('/',     [PushCampaignController::class, 'index'])->name('index');
        Route::post('/send', [PushCampaignController::class, 'send'])->name('send');
    });
});
