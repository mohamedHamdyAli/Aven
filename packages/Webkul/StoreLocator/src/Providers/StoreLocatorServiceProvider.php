<?php

namespace Webkul\StoreLocator\Providers;

use Illuminate\Support\ServiceProvider;

class StoreLocatorServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }
}
