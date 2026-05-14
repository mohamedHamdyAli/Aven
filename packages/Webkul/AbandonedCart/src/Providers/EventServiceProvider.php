<?php

namespace Webkul\AbandonedCart\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Webkul\AbandonedCart\Listeners\CartActivityTracker;
use Webkul\AbandonedCart\Listeners\FraudOrderListener;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        'checkout.cart.add.after' => [
            [CartActivityTracker::class, 'onCartActivity'],
        ],

        'checkout.cart.update.after' => [
            [CartActivityTracker::class, 'onCartActivity'],
        ],

        'checkout.cart.collect.totals.after' => [
            [CartActivityTracker::class, 'onCartActivity'],
        ],

        'checkout.order.save.before' => [
            [FraudOrderListener::class, 'scoreOrder'],
        ],

        'checkout.order.save.after' => [
            [CartActivityTracker::class, 'onOrderCreated'],
        ],
    ];
}
