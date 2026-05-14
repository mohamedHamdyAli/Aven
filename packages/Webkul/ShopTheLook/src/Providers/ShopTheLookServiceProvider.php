<?php

namespace Webkul\ShopTheLook\Providers;

use Illuminate\Support\ServiceProvider;

class ShopTheLookServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'shop-the-look');
        $this->loadRoutesFrom(__DIR__.'/../Routes/admin-routes.php');
    }
}
