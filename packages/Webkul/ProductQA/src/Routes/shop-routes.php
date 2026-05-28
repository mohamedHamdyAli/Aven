<?php

use Illuminate\Support\Facades\Route;
use Webkul\ProductQA\Http\Controllers\Shop\ProductQAController;

Route::group(['middleware' => ['web', 'locale', 'theme', 'currency']], function () {
    Route::post('/product-qa/ask', [ProductQAController::class, 'store'])->name('shop.product_qa.store');
    Route::get('/product-qa/{productId}/questions', [ProductQAController::class, 'forProduct'])->name('shop.product_qa.for_product');
});
