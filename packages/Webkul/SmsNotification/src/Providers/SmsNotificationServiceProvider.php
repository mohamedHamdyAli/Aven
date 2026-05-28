<?php

namespace Webkul\SmsNotification\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Webkul\SmsNotification\Listeners\SmsOrderListener;
use Webkul\SmsNotification\Services\SmsService;

class SmsNotificationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SmsService::class);
    }

    public function boot(): void
    {
        Event::listen('checkout.order.save.after', [SmsOrderListener::class, 'onOrderSaved']);
        Event::listen('sales.shipment.save.after', [SmsOrderListener::class, 'onShipmentSaved']);
        Event::listen('sales.order.update-status.after', [SmsOrderListener::class, 'onOrderStatusUpdated']);
    }
}
