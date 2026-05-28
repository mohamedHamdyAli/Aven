<?php

use Illuminate\Support\Facades\Route;
use Webkul\ProductQA\Http\Controllers\Admin\ProductQAController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => config('app.admin_path', 'admin')], function () {
    Route::prefix('product-qa')->name('admin.product_qa.')->group(function () {
        Route::get('/', [ProductQAController::class, 'index'])->name('index');
        Route::post('/{id}/answer', [ProductQAController::class, 'answer'])->name('answer');
        Route::post('/{id}/reject', [ProductQAController::class, 'reject'])->name('reject');
        Route::delete('/{id}', [ProductQAController::class, 'destroy'])->name('destroy');
    });
});
