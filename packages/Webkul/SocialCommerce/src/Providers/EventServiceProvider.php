<?php

namespace Webkul\SocialCommerce\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Webkul\SocialCommerce\Listeners\InjectTrackingPixels;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        'bagisto.shop.layout.head.after' => [
            [InjectTrackingPixels::class, 'onHeadAfter'],
        ],
        'bagisto.shop.layout.body.after' => [
            [InjectTrackingPixels::class, 'onBodyAfter'],
        ],
    ];
}
