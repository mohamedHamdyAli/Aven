<?php

namespace Webkul\Bosta\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Webkul\Bosta\Listeners\CreateBostaDelivery;
use Webkul\Bosta\Services\BostaApiService;

class BostaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../Config/carriers.php', 'carriers');

        $this->app->singleton(BostaApiService::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->loadRoutesFrom(__DIR__.'/../Routes/admin-routes.php');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'bosta');

        Event::listen('checkout.order.save.after', [CreateBostaDelivery::class, 'onOrderSaved']);
    }
}
