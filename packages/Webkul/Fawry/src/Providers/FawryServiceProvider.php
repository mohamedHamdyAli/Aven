<?php

namespace Webkul\Fawry\Providers;

use Illuminate\Support\ServiceProvider;

class FawryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../Config/paymentmethods.php', 'payment_methods');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->loadRoutesFrom(__DIR__.'/../Routes/shop-routes.php');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'fawry');
    }
}
