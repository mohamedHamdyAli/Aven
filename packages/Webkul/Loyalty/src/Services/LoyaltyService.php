<?php

namespace Webkul\Loyalty\Services;

use Illuminate\Support\Facades\DB;
use Webkul\Loyalty\Models\CustomerLoyaltyPoints;
use Webkul\Loyalty\Models\CustomerLoyaltyTransaction;

class LoyaltyService
{
    public function getBalance(int $customerId): float
    {
        return (float) CustomerLoyaltyPoints::firstOrCreate(
            ['customer_id' => $customerId],
            ['balance' => 0]
        )->balance;
    }

    public function earn(int $customerId, int $orderId, float $orderTotal): void
    {
        $rate = (float) (core()->getConfigData('general.loyalty.settings.earn_rate') ?? 1);
        $points = floor($orderTotal * $rate);
        if ($points <= 0) {
            return;
        }
        $this->credit($customerId, $points, $orderId, 'earn', "Earned for order #{$orderId}");
    }

    public function credit(int $customerId, float $points, ?int $orderId, string $type, string $desc): void
    {
        DB::transaction(function () use ($customerId, $points, $orderId, $type, $desc) {
            $record = CustomerLoyaltyPoints::lockForUpdate()->firstOrCreate(
                ['customer_id' => $customerId],
                ['balance' => 0]
            );
            $record->balance = $record->balance + $points;
            $record->save();
            CustomerLoyaltyTransaction::create([
                'customer_id'   => $customerId,
                'order_id'      => $orderId,
                'type'          => $type,
                'points'        => $points,
                'balance_after' => $record->balance,
                'description'   => $desc,
            ]);
        });
    }

    public function debit(int $customerId, float $points, ?int $orderId, string $desc): bool
    {
        return DB::transaction(function () use ($customerId, $points, $orderId, $desc) {
            $record = CustomerLoyaltyPoints::lockForUpdate()->where('customer_id', $customerId)->first();
            if (! $record || $record->balance < $points) {
                return false;
            }
            $record->balance -= $points;
            $record->save();
            CustomerLoyaltyTransaction::create([
                'customer_id'   => $customerId,
                'order_id'      => $orderId,
                'type'          => 'redeem',
                'points'        => -$points,
                'balance_after' => $record->balance,
                'description'   => $desc,
            ]);

            return true;
        });
    }

    public function applyToCart(int $cartId, int $customerId, float $points): float
    {
        $balance = $this->getBalance($customerId);
        $minRedeem = (float) (core()->getConfigData('general.loyalty.settings.min_redeem') ?? 100);
        if ($points < $minRedeem || $points > $balance) {
            return 0;
        }

        $redeemRate = (float) (core()->getConfigData('general.loyalty.settings.redeem_rate') ?? 0.25);
        $discount = round($points * $redeemRate, 4);

        $cart = DB::table('cart')->where('id', $cartId)->first();
        $discount = min($discount, (float) $cart->grand_total);

        DB::table('cart')->where('id', $cartId)->update(['loyalty_points_applied' => $discount]);

        return $discount;
    }

    public function removeFromCart(int $cartId): void
    {
        DB::table('cart')->where('id', $cartId)->update(['loyalty_points_applied' => 0]);
    }
}
