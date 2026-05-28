<?php

namespace Webkul\ProductQA\Providers;

use Illuminate\Support\ServiceProvider;

class ProductQAServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->loadRoutesFrom(__DIR__.'/../Routes/admin-routes.php');
        $this->loadRoutesFrom(__DIR__.'/../Routes/shop-routes.php');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'product_qa');

        $this->mergeConfigFrom(__DIR__.'/../Config/admin-menu.php', 'menu.admin');
    }
}
