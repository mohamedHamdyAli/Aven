<?php

namespace Webkul\Referral\Listeners;

use Webkul\Referral\Services\ReferralService;

class ReferralOrderListener
{
    public function __construct(private ReferralService $service) {}

    public function onOrderSaved($order): void
    {
        $this->service->rewardOnFirstOrder($order);
    }
}
