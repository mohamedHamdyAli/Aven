<?php

namespace Webkul\SizeGuide\Providers;

use Illuminate\Support\ServiceProvider;

class SizeGuideServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(dirname(__DIR__).'/Database/Migrations');
        $this->loadViewsFrom(dirname(__DIR__).'/Resources/views', 'size-guide');
        $this->loadTranslationsFrom(dirname(__DIR__).'/Resources/lang', 'size-guide');
        $this->loadRoutesFrom(dirname(__DIR__).'/Routes/admin-routes.php');
        $this->loadRoutesFrom(dirname(__DIR__).'/Routes/shop-routes.php');
    }
}
