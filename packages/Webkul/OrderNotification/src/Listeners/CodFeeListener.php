<?php

namespace Webkul\OrderNotification\Listeners;

use Illuminate\Support\Facades\DB;

class CodFeeListener
{
    public function onTotalsCollected(object $cart): void
    {
        $fee = (float) core()->getConfigData('sales.payment_methods.cashondelivery.extra_charge');

        if ($fee <= 0) {
            return;
        }

        $isCod = $cart->payment?->method === 'cashondelivery';

        // Remove fee first so recalculation is clean
        if (! $isCod && $cart->cod_fee > 0) {
            DB::table('cart')->where('id', $cart->id)->update([
                'cod_fee'     => 0,
                'grand_total' => round($cart->grand_total - $cart->cod_fee, 2),
                'base_grand_total' => round($cart->base_grand_total - $cart->cod_fee, 2),
            ]);

            return;
        }

        if ($isCod && (float) $cart->cod_fee !== $fee) {
            $prev = (float) $cart->cod_fee;
            DB::table('cart')->where('id', $cart->id)->update([
                'cod_fee'          => $fee,
                'grand_total'      => round($cart->grand_total - $prev + $fee, 2),
                'base_grand_total' => round($cart->base_grand_total - $prev + $fee, 2),
            ]);
        }
    }

    public function onOrderSaving(array $data): void
    {
        // Propagate cod_fee from cart to order data array
        if (! empty($data['cart_id'])) {
            $codFee = DB::table('cart')->where('id', $data['cart_id'])->value('cod_fee');
            if ($codFee) {
                $data['cod_fee'] = $codFee;
            }
        }
    }

    public function onOrderSaved(object $order): void
    {
        // Sync cod_fee from cart to order after save
        if ($order->cart_id) {
            $codFee = DB::table('cart')->where('id', $order->cart_id)->value('cod_fee');
            if ($codFee) {
                DB::table('orders')->where('id', $order->id)->update(['cod_fee' => $codFee]);
            }
        }
    }
}
