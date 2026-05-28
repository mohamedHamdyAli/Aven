<?php

namespace Webkul\CostManagement\Providers;

use Illuminate\Support\ServiceProvider;

class CostManagementServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->app->register(EventServiceProvider::class);

        $this->loadRoutesFrom(__DIR__.'/../Routes/admin-routes.php');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'cost_management');

        $this->mergeConfigFrom(__DIR__.'/../Config/admin-menu.php', 'menu.admin');
    }
}
