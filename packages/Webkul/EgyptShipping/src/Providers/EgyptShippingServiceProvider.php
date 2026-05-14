<?php

namespace Webkul\EgyptShipping\Providers;

use Illuminate\Support\ServiceProvider;

class EgyptShippingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/carriers.php', 'carriers'
        );
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(dirname(__DIR__).'/Database/Migrations');
        $this->loadViewsFrom(dirname(__DIR__).'/Resources/views', 'egypt-shipping');
        $this->loadTranslationsFrom(dirname(__DIR__).'/Resources/lang', 'egypt-shipping');
        $this->loadRoutesFrom(dirname(__DIR__).'/Routes/admin-routes.php');
        $this->loadRoutesFrom(dirname(__DIR__).'/Routes/shop-routes.php');
    }
}
