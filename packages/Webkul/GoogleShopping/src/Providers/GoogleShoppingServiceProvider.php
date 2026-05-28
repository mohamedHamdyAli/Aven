<?php

namespace Webkul\GoogleShopping\Providers;

use Illuminate\Support\ServiceProvider;

class GoogleShoppingServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/shop-routes.php');
    }
}
