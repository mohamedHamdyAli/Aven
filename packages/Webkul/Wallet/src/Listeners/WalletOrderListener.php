<?php

namespace Webkul\Wallet\Listeners;

use Illuminate\Support\Facades\DB;
use Webkul\Wallet\Services\WalletService;

class WalletOrderListener
{
    public function __construct(protected WalletService $wallet) {}

    public function onOrderSaved($order): void
    {
        if (! $order->customer_id) {
            return;
        }

        $creditApplied = (float) DB::table('cart')
            ->where('id', $order->cart_id)
            ->value('store_credit_applied');

        if ($creditApplied <= 0) {
            return;
        }

        $debited = $this->wallet->debit(
            $order->customer_id,
            $creditApplied,
            "Applied to order #{$order->increment_id}",
            $order->id
        );

        if ($debited) {
            DB::table('orders')
                ->where('id', $order->id)
                ->update(['store_credit_applied' => $creditApplied]);
        }
    }
}
