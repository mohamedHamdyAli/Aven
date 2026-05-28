<?php

use Illuminate\Support\Facades\Route;
use Webkul\CostManagement\Http\Controllers\AdSpendController;
use Webkul\CostManagement\Http\Controllers\GeneralExpenseController;
use Webkul\CostManagement\Http\Controllers\ProductCostController;
use Webkul\CostManagement\Http\Controllers\ProfitReportController;
use Webkul\CostManagement\Http\Controllers\ShareholderController;
use Webkul\CostManagement\Http\Controllers\ProfitDistributionController;
use Webkul\CostManagement\Http\Controllers\ShareholderPortalController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => config('app.admin_path', 'admin')], function () {
    Route::prefix('cost-management')->name('admin.cost_management.')->group(function () {

        Route::prefix('products')->name('products.')->group(function () {
            Route::get('/', [ProductCostController::class, 'index'])->name('index');
            Route::get('/{id}/edit', [ProductCostController::class, 'edit'])->name('edit');
            Route::put('/{id}', [ProductCostController::class, 'update'])->name('update');
        });

        Route::prefix('expenses')->name('expenses.')->group(function () {
            Route::get('/', [GeneralExpenseController::class, 'index'])->name('index');
            Route::post('/', [GeneralExpenseController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [GeneralExpenseController::class, 'edit'])->name('edit');
            Route::put('/{id}', [GeneralExpenseController::class, 'update'])->name('update');
            Route::delete('/{id}', [GeneralExpenseController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('report')->name('report.')->group(function () {
            Route::get('/', [ProfitReportController::class, 'index'])->name('index');
            Route::get('/data', [ProfitReportController::class, 'data'])->name('data');
        });

        Route::prefix('ad-spend')->name('ad_spend.')->group(function () {
            Route::post('/', [AdSpendController::class, 'store'])->name('store');
            Route::delete('/{id}', [AdSpendController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('shareholders')->name('shareholders.')->group(function () {
            Route::get('/', [ShareholderController::class, 'index'])->name('index');
            Route::post('/', [ShareholderController::class, 'store'])->name('store');
            Route::put('/{shareholder}', [ShareholderController::class, 'update'])->name('update');
            Route::delete('/{shareholder}', [ShareholderController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('distributions')->name('distributions.')->group(function () {
            Route::get('/', [ProfitDistributionController::class, 'index'])->name('index');
            Route::get('/create', [ProfitDistributionController::class, 'create'])->name('create');
            Route::post('/', [ProfitDistributionController::class, 'store'])->name('store');
            Route::get('/{distribution}', [ProfitDistributionController::class, 'show'])->name('show');
            Route::delete('/{distribution}', [ProfitDistributionController::class, 'destroy'])->name('destroy');
        });
    });
});

// Shareholder self-service portal (public, web middleware only)
Route::group(['middleware' => ['web'], 'prefix' => 'shareholder-portal', 'as' => 'shareholder.portal.'], function () {
    Route::get('/', [ShareholderPortalController::class, 'showLogin'])->name('login');
    Route::post('/login', [ShareholderPortalController::class, 'login'])->name('do_login');
    Route::get('/dashboard', [ShareholderPortalController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [ShareholderPortalController::class, 'logout'])->name('logout');
});
