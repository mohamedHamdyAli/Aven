<?php

namespace Webkul\SocialCommerce\Providers;

use Illuminate\Support\ServiceProvider;

class SocialCommerceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../Config/system.php', 'core');
        $this->mergeConfigFrom(__DIR__.'/../Config/menu.php', 'menu');
        $this->mergeConfigFrom(__DIR__.'/../Config/acl.php', 'acl');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->loadRoutesFrom(__DIR__.'/../Routes/admin-routes.php');
        $this->loadRoutesFrom(__DIR__.'/../Routes/webhook-routes.php');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'social-commerce');
        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'social-commerce');

        $this->app->register(EventServiceProvider::class);
    }
}
