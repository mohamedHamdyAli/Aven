<?php

namespace Webkul\BulkDeal\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Webkul\BulkDeal\Listeners\Cart;

class BulkDealServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        Event::listen('checkout.cart.collect.totals.before', [Cart::class, 'applyBulkDeals']);
    }
}
