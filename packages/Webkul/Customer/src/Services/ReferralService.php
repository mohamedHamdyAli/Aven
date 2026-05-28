<?php

namespace Webkul\Customer\Services;

use Illuminate\Support\Str;
use Webkul\CartRule\Models\CartRule;
use Webkul\CartRule\Models\CartRuleCoupon;
use Webkul\Customer\Models\CustomerReferral;

class ReferralService
{
    public function isEnabled(): bool
    {
        return (bool) core()->getConfigData('general.referral.settings.enabled');
    }

    public function getOrCreateCode(int $customerId): string
    {
        $referral = CustomerReferral::where('referrer_id', $customerId)
            ->whereNull('referred_customer_id')
            ->first();

        if ($referral) {
            return $referral->code;
        }

        $code = strtoupper(Str::random(8));

        while (CustomerReferral::where('code', $code)->exists()) {
            $code = strtoupper(Str::random(8));
        }

        CustomerReferral::create([
            'referrer_id' => $customerId,
            'code'        => $code,
        ]);

        return $code;
    }

    public function getReferralUrl(int $customerId): string
    {
        $code = $this->getOrCreateCode($customerId);

        return route('shop.customer.register.index') . '?ref=' . $code;
    }

    public function processRegistration(string $code, int $newCustomerId): void
    {
        $referral = CustomerReferral::where('code', $code)
            ->whereNull('referred_customer_id')
            ->first();

        if (! $referral || $referral->referrer_id === $newCustomerId) {
            return;
        }

        $referral->update(['referred_customer_id' => $newCustomerId]);
    }

    public function issueRewardIfEligible(int $customerId, int $orderId): void
    {
        $referral = CustomerReferral::where('referred_customer_id', $customerId)
            ->where('order_placed', false)
            ->first();

        if (! $referral) {
            return;
        }

        $referral->update(['order_placed' => true]);

        if (! $this->isEnabled()) {
            return;
        }

        $rewardType   = core()->getConfigData('general.referral.settings.reward_type')   ?? 'fixed';
        $rewardAmount = (float)(core()->getConfigData('general.referral.settings.reward_amount') ?? 50);

        $couponCode = 'REF-' . $referral->code . '-' . strtoupper(Str::random(4));

        $rule = CartRule::create([
            'name'               => 'Referral Reward',
            'description'        => 'Referral reward coupon',
            'coupon_type'        => 1,
            'use_auto_generation'=> 0,
            'discount_type'      => $rewardType === 'percent' ? 'percent_of_product' : 'fixed',
            'discount_amount'    => $rewardAmount,
            'times_used'         => 0,
            'usage_per_coupon'   => 1,
            'usage_per_customer' => 1,
            'status'             => 1,
            'starts_at'          => now(),
            'ends_at'            => now()->addDays(60),
        ]);

        CartRuleCoupon::create([
            'cart_rule_id' => $rule->id,
            'code'         => $couponCode,
            'usage_limit'  => 1,
            'times_used'   => 0,
            'is_primary'   => 1,
        ]);

        $referral->update(['reward_issued' => true]);

        // Store coupon for referrer to see in their account
        \Illuminate\Support\Facades\DB::table('coupon_assignment_campaigns')->insert([
            'name'         => "Referral Reward for order #{$orderId}",
            'cart_rule_id' => $rule->id,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);
    }

    public function getReferralStats(int $customerId): array
    {
        $code = CustomerReferral::where('referrer_id', $customerId)
            ->whereNull('referred_customer_id')
            ->value('code');

        $total   = CustomerReferral::where('referrer_id', $customerId)->whereNotNull('referred_customer_id')->count();
        $rewarded = CustomerReferral::where('referrer_id', $customerId)->where('reward_issued', true)->count();

        return [
            'code'     => $code ?? $this->getOrCreateCode($customerId),
            'url'      => $this->getReferralUrl($customerId),
            'total'    => $total,
            'rewarded' => $rewarded,
        ];
    }
}
