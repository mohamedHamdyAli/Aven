<?php

use Illuminate\Support\Facades\Route;
use Webkul\SizeGuide\Http\Controllers\Admin\SizeGuideController;

Route::group([
    'prefix'     => config('app.admin_url', 'admin'),
    'middleware' => ['web', 'admin'],
], function () {
    Route::get('size-guide',          [SizeGuideController::class, 'index'])->name('admin.size-guide.index');
    Route::get('size-guide/create',   [SizeGuideController::class, 'create'])->name('admin.size-guide.create');
    Route::post('size-guide',         [SizeGuideController::class, 'store'])->name('admin.size-guide.store');
    Route::get('size-guide/{id}/edit',[SizeGuideController::class, 'edit'])->name('admin.size-guide.edit');
    Route::put('size-guide/{id}',     [SizeGuideController::class, 'update'])->name('admin.size-guide.update');
    Route::delete('size-guide/{id}',  [SizeGuideController::class, 'destroy'])->name('admin.size-guide.destroy');
    Route::post('size-guide/assign-product', [SizeGuideController::class, 'assignProduct'])->name('admin.size-guide.assign-product');
    Route::get('size-guide/products/search',  [SizeGuideController::class, 'searchProducts'])->name('admin.size-guide.products.search');
});
