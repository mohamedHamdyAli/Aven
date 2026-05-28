<?php

use Illuminate\Support\Facades\Route;
use Webkul\Blog\Http\Controllers\Admin\BlogController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => config('app.admin_path', 'admin')], function () {
    Route::prefix('blog')->name('admin.blog.')->group(function () {
        Route::get('/',           [BlogController::class, 'index'])->name('index');
        Route::get('/create',     [BlogController::class, 'create'])->name('create');
        Route::post('/',          [BlogController::class, 'store'])->name('store');
        Route::get('/{id}/edit',  [BlogController::class, 'edit'])->name('edit');
        Route::put('/{id}',       [BlogController::class, 'update'])->name('update');
        Route::delete('/{id}',    [BlogController::class, 'destroy'])->name('destroy');
    });
});
