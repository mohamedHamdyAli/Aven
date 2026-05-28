<?php

namespace Webkul\Shop\Listeners;

use Webkul\Customer\Services\LoyaltyService;
use Webkul\Customer\Services\ReferralService;

class LoyaltyAndReferral
{
    public function __construct(
        protected LoyaltyService  $loyalty,
        protected ReferralService $referral,
    ) {}

    public function afterCustomerRegistered($customer): void
    {
        try {
            $refCode = session()->pull('referral_code');

            if ($refCode) {
                $this->referral->processRegistration($refCode, $customer->id);
            }
        } catch (\Exception $e) {
            report($e);
        }
    }

    public function afterOrderCreated($order): void
    {
        try {
            if (! $order->customer_id) {
                return;
            }

            // Confirm any pending loyalty redemption coupon
            if ($order->coupon_code && str_starts_with($order->coupon_code, 'LP-')) {
                $this->loyalty->confirmRedemption($order->id, $order->coupon_code);
            }

            // Award loyalty points for this order (if enabled)
            if ($this->loyalty->isEnabled()) {
                $this->loyalty->awardForOrder(
                    $order->customer_id,
                    (float) $order->grand_total,
                    $order->id
                );
            }

            // Issue referral reward if this customer was referred and it's their first order
            $this->referral->issueRewardIfEligible($order->customer_id, $order->id);
        } catch (\Exception $e) {
            report($e);
        }
    }
}
