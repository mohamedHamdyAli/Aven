<?php

namespace Webkul\AbandonedCart\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Webkul\AbandonedCart\Console\Commands\ProcessAbandonedCarts;
use Webkul\AbandonedCart\Http\Middleware\CartRecoveryCookie;

class AbandonedCartServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../Config/system.php', 'core'
        );

        $this->mergeConfigFrom(
            __DIR__.'/../Config/disposable-domains.php', 'abandoned-cart.disposable_domains'
        );
    }

    public function boot(Router $router): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->loadRoutesFrom(__DIR__.'/../Routes/admin-routes.php');
        $this->loadRoutesFrom(__DIR__.'/../Routes/shop-routes.php');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'abandoned-cart');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'abandoned-cart');

        // Append recovery cookie tracking to the shop middleware group
        $router->pushMiddlewareToGroup('shop', CartRecoveryCookie::class);

        if ($this->app->runningInConsole()) {
            $this->commands([ProcessAbandonedCarts::class]);
        }

        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->command('abandoned-cart:process')->everyFifteenMinutes();
        });

        $this->app->register(EventServiceProvider::class);
    }
}
