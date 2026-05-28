<?php

namespace Webkul\OrderNotification\Providers;

use Illuminate\Support\ServiceProvider;
use Webkul\OrderNotification\Listeners\CodFeeListener;
use Webkul\OrderNotification\Listeners\LowStockAlertListener;
use Webkul\OrderNotification\Listeners\OrderNotificationListener;

class EventServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // WhatsApp order notifications
        $this->app['events']->listen('checkout.order.save.after', [OrderNotificationListener::class, 'onOrderPlaced']);
        $this->app['events']->listen('sales.shipment.save.after', [OrderNotificationListener::class, 'onShipmentCreated']);
        $this->app['events']->listen('sales.order.update-status.after', [OrderNotificationListener::class, 'onStatusUpdated']);

        // COD fee
        $this->app['events']->listen('checkout.cart.collect.totals.after', [CodFeeListener::class, 'onTotalsCollected']);
        $this->app['events']->listen('checkout.order.save.after', [CodFeeListener::class, 'onOrderSaved']);

        // Low stock alerts
        $this->app['events']->listen('catalog.product.update.after', [LowStockAlertListener::class, 'onProductUpdated']);
    }
}
