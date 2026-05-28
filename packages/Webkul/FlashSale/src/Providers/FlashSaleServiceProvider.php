<?php

namespace Webkul\FlashSale\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use Webkul\FlashSale\Console\Commands\SyncFlashSales;
use Webkul\FlashSale\Services\FlashSaleService;

class FlashSaleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(FlashSaleService::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->loadRoutesFrom(__DIR__.'/../Routes/admin-routes.php');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'flash-sale');

        if ($this->app->runningInConsole()) {
            $this->commands([SyncFlashSales::class]);
        }

        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->command('flash:sync')->everyFiveMinutes();
        });
    }
}
