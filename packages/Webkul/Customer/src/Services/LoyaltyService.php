<?php

namespace Webkul\Customer\Services;

use Illuminate\Support\Facades\DB;
use Webkul\CartRule\Models\CartRule;
use Webkul\CartRule\Models\CartRuleCoupon;
use Webkul\Customer\Models\CustomerLoyaltyPoint;
use Webkul\Customer\Models\CustomerLoyaltyTransaction;

class LoyaltyService
{
    public function isEnabled(): bool
    {
        return (bool) core()->getConfigData('general.loyalty.settings.enabled');
    }

    public function getBalance(int $customerId): int
    {
        return CustomerLoyaltyPoint::where('customer_id', $customerId)->value('balance') ?? 0;
    }

    /** Points earned per 1 unit of currency spent (e.g. 1 point per 10 EGP = rate of 0.1) */
    public function earnRate(): float
    {
        return (float)(core()->getConfigData('general.loyalty.settings.earn_rate') ?? 10);
    }

    /** How much 1 point is worth in currency (e.g. 1 point = 0.1 EGP) */
    public function redeemRate(): float
    {
        return (float)(core()->getConfigData('general.loyalty.settings.redeem_rate') ?? 0.10);
    }

    public function minRedeem(): int
    {
        return (int)(core()->getConfigData('general.loyalty.settings.min_redeem') ?? 50);
    }

    public function pointsValueFormatted(int $points): string
    {
        return core()->formatPrice($points * $this->redeemRate());
    }

    public function awardForOrder(int $customerId, float $orderTotal, int $orderId): int
    {
        $points = (int) floor($orderTotal / $this->earnRate());

        if ($points <= 0) {
            return 0;
        }

        DB::transaction(function () use ($customerId, $points, $orderId) {
            CustomerLoyaltyPoint::updateOrCreate(
                ['customer_id' => $customerId],
                ['balance'     => DB::raw("balance + {$points}")]
            );

            CustomerLoyaltyTransaction::create([
                'customer_id' => $customerId,
                'points'      => $points,
                'type'        => 'earn',
                'description' => "Earned for order #{$orderId}",
                'order_id'    => $orderId,
            ]);
        });

        return $points;
    }

    public function redeem(int $customerId, int $points): array
    {
        $balance = $this->getBalance($customerId);

        if ($balance < $points || $points < $this->minRedeem()) {
            return ['success' => false, 'message' => 'Insufficient points or below minimum redemption.'];
        }

        $discountAmount = round($points * $this->redeemRate(), 2);

        $couponCode = 'LP-' . strtoupper(base_convert(crc32($customerId . $points . time()), 10, 36));

        DB::transaction(function () use ($customerId, $points, $discountAmount, $couponCode) {
            $rule = CartRule::create([
                'name'            => 'Loyalty Redemption',
                'description'     => 'Auto-generated loyalty points redemption coupon',
                'coupon_type'     => 1,
                'use_auto_generation' => 0,
                'discount_type'   => 'fixed',
                'discount_amount' => $discountAmount,
                'times_used'      => 0,
                'usage_per_coupon'=> 1,
                'usage_per_customer' => 1,
                'status'          => 1,
                'starts_at'       => now(),
                'ends_at'         => now()->addDays(30),
            ]);

            $coupon = CartRuleCoupon::create([
                'cart_rule_id' => $rule->id,
                'code'         => $couponCode,
                'usage_limit'  => 1,
                'times_used'   => 0,
                'is_primary'   => 1,
            ]);

            DB::table('loyalty_redemptions')->insert([
                'customer_id'       => $customerId,
                'cart_rule_coupon_id' => $coupon->id,
                'points_redeemed'   => $points,
                'discount_amount'   => $discountAmount,
                'status'            => 'pending',
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        });

        return ['success' => true, 'coupon_code' => $couponCode, 'discount' => $discountAmount];
    }

    public function confirmRedemption(int $orderId, string $couponCode): void
    {
        $redemption = DB::table('loyalty_redemptions')
            ->join('cart_rule_coupons', 'cart_rule_coupons.id', '=', 'loyalty_redemptions.cart_rule_coupon_id')
            ->where('cart_rule_coupons.code', $couponCode)
            ->where('loyalty_redemptions.status', 'pending')
            ->select('loyalty_redemptions.*')
            ->first();

        if (! $redemption) {
            return;
        }

        DB::transaction(function () use ($redemption, $orderId) {
            CustomerLoyaltyPoint::where('customer_id', $redemption->customer_id)
                ->decrement('balance', $redemption->points_redeemed);

            CustomerLoyaltyTransaction::create([
                'customer_id' => $redemption->customer_id,
                'points'      => -$redemption->points_redeemed,
                'type'        => 'redeem',
                'description' => "Redeemed for order #{$orderId}",
                'order_id'    => $orderId,
            ]);

            DB::table('loyalty_redemptions')
                ->where('id', $redemption->id)
                ->update(['status' => 'confirmed', 'order_id' => $orderId, 'updated_at' => now()]);
        });
    }

    public function getTransactions(int $customerId, int $limit = 20): \Illuminate\Support\Collection
    {
        return CustomerLoyaltyTransaction::where('customer_id', $customerId)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }
}
