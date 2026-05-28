<?php

namespace Webkul\Referral\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Webkul\Referral\Listeners\ReferralOrderListener;
use Webkul\Referral\Listeners\ReferralRegistrationListener;
use Webkul\Referral\Services\ReferralService;

class ReferralServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ReferralService::class);
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/shop-routes.php');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'referral');

        Event::listen('checkout.order.save.after', [ReferralOrderListener::class, 'onOrderSaved']);
        Event::listen('customer.registration.after', [ReferralRegistrationListener::class, 'handle']);
        Event::listen('customer.after.create', [ReferralRegistrationListener::class, 'handle']);
    }
}
