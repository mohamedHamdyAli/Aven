<?php

namespace Webkul\GiftCard\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Webkul\GiftCard\Listeners\GiftCardOrderListener;
use Webkul\GiftCard\Services\GiftCardService;

class GiftCardServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(GiftCardService::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->loadRoutesFrom(__DIR__.'/../Routes/admin-routes.php');
        $this->loadRoutesFrom(__DIR__.'/../Routes/shop-routes.php');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'gift-card');

        Event::listen('checkout.order.save.after', [GiftCardOrderListener::class, 'onOrderSaved']);
    }
}
