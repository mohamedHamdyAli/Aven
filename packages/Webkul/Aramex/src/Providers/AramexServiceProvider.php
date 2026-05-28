<?php

namespace Webkul\Aramex\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Webkul\Aramex\Listeners\CreateAramexDelivery;
use Webkul\Aramex\Services\AramexApiService;

class AramexServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../Config/carriers.php', 'carriers');

        $this->app->singleton(AramexApiService::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->loadRoutesFrom(__DIR__.'/../Routes/admin-routes.php');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'aramex');

        Event::listen('checkout.order.save.after', [CreateAramexDelivery::class, 'onOrderSaved']);
    }
}
