<?php

namespace Webkul\PushNotification\Providers;

use Illuminate\Support\ServiceProvider;
use Webkul\PushNotification\Console\GenerateVapidKeysCommand;
use Webkul\PushNotification\Services\PushService;

class PushNotificationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../Config/push-notification.php', 'push-notification');
        $this->app->singleton(PushService::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin-routes.php');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/shop-routes.php');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'push-notification');

        $this->mergeConfigFrom(__DIR__ . '/../Config/admin-menu.php', 'menu.admin');

        if ($this->app->runningInConsole()) {
            $this->commands([GenerateVapidKeysCommand::class]);
        }
    }
}
