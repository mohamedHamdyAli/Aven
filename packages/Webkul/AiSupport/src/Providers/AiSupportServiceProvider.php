<?php

namespace Webkul\AiSupport\Providers;

use Illuminate\Support\ServiceProvider;

class AiSupportServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../Config/system.php', 'core-config-settings.ai-support'
        );
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->loadRoutesFrom(__DIR__.'/../Routes/admin-routes.php');
        $this->loadRoutesFrom(__DIR__.'/../Routes/api.php');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'ai-support');

        $this->publishes([
            __DIR__.'/../Resources/views' => resource_path('views/vendor/ai-support'),
        ], 'ai-support-views');

        config()->set('menu-config', array_merge(
            config('menu-config', []),
            require __DIR__.'/../Config/menu.php'
        ));
    }
}
