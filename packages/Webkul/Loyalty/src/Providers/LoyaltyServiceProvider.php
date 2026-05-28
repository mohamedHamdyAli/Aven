<?php

namespace Webkul\Loyalty\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Webkul\Loyalty\Listeners\LoyaltyOrderListener;
use Webkul\Loyalty\Services\LoyaltyService;

class LoyaltyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(LoyaltyService::class);
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/shop-routes.php');
        Event::listen('checkout.order.save.after', [LoyaltyOrderListener::class, 'onOrderSaved']);
    }
}
