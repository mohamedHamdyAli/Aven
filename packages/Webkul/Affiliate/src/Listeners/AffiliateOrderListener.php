<?php

namespace Webkul\Affiliate\Listeners;

use Webkul\Affiliate\Models\Affiliate;
use Webkul\Affiliate\Models\AffiliateCommission;

class AffiliateOrderListener
{
    public function onOrderSaved($order): void
    {
        $code = session('affiliate_code');

        if (! $code) {
            return;
        }

        $affiliate = Affiliate::where('code', $code)
            ->where('status', 'approved')
            ->first();

        if (! $affiliate) {
            return;
        }

        $commission = round(
            (float) $order->base_grand_total * ($affiliate->commission_rate / 100),
            4
        );

        AffiliateCommission::create([
            'affiliate_id' => $affiliate->id,
            'order_id'     => $order->id,
            'order_total'  => $order->base_grand_total,
            'commission'   => $commission,
            'status'       => 'pending',
        ]);

        $affiliate->increment('total_earned', $commission);

        session()->forget('affiliate_code');
    }
}
