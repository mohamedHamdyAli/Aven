<?php

namespace Webkul\Wallet\Providers;

use Illuminate\Support\ServiceProvider;
use Webkul\Wallet\Listeners\WalletOrderListener;
use Webkul\Wallet\Services\WalletService;

class WalletServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(WalletService::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin-routes.php');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/shop-routes.php');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'wallet');

        $this->mergeConfigFrom(__DIR__ . '/../Config/admin-menu.php', 'menu.admin');

        $this->app['events']->listen(
            'checkout.order.save.after',
            [WalletOrderListener::class, 'onOrderSaved']
        );
    }
}
