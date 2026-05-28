<?php

namespace Webkul\Affiliate\Providers;

use Illuminate\Support\ServiceProvider;
use Webkul\Affiliate\Listeners\AffiliateOrderListener;

class AffiliateServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin-routes.php');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/shop-routes.php');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'affiliate');

        $this->mergeConfigFrom(__DIR__ . '/../Config/admin-menu.php', 'menu.admin');

        $this->app['events']->listen(
            'checkout.order.save.after',
            [AffiliateOrderListener::class, 'onOrderSaved']
        );
    }
}
