<?php

namespace Webkul\Loyalty\Listeners;

use Illuminate\Support\Facades\DB;
use Webkul\Loyalty\Services\LoyaltyService;

class LoyaltyOrderListener
{
    public function __construct(private LoyaltyService $loyalty) {}

    public function onOrderSaved($order): void
    {
        if (! $order->customer_id) {
            return;
        }

        if (! core()->getConfigData('general.loyalty.settings.enabled')) {
            return;
        }

        $pointsApplied = (float) DB::table('cart')->where('id', $order->cart_id)->value('loyalty_points_applied');

        if ($pointsApplied > 0) {
            DB::table('orders')->where('id', $order->id)->update(['loyalty_points_applied' => $pointsApplied]);
            $redeemRate = (float) (core()->getConfigData('general.loyalty.settings.redeem_rate') ?? 0.25);
            $pointsUsed = $redeemRate > 0 ? round($pointsApplied / $redeemRate) : 0;
            $this->loyalty->debit($order->customer_id, $pointsUsed, $order->id, "Redeemed on order #{$order->increment_id}");
        }

        $earnBase = max(0, (float) $order->grand_total - $pointsApplied);
        $this->loyalty->earn($order->customer_id, $order->id, $earnBase);
    }
}
