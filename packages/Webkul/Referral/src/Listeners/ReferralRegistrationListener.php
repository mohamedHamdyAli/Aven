<?php

namespace Webkul\Referral\Listeners;

use Illuminate\Support\Facades\Session;
use Webkul\Referral\Services\ReferralService;

class ReferralRegistrationListener
{
    public function __construct(private ReferralService $service) {}

    public function handle($customer): void
    {
        $code = Session::get('referral_code');

        if (! $code) {
            return;
        }

        $this->service->recordRegistration($code, $customer->id, $customer->email);

        Session::forget('referral_code');
    }
}
