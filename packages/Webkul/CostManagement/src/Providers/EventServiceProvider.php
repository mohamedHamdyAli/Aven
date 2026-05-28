<?php

namespace Webkul\CostManagement\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Webkul\CostManagement\Listeners\FinancialTransactionListener;

class EventServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Event::listen(
            'checkout.order.save.after',
            [FinancialTransactionListener::class, 'onOrderSaved']
        );

        Event::listen(
            'sales.refund.save.after',
            [FinancialTransactionListener::class, 'onRefundSaved']
        );
    }
}
