<?php

namespace Webkul\CostManagement\Listeners;

use Webkul\CostManagement\Models\FinancialTransaction;

class FinancialTransactionListener
{
    public function onOrderSaved($order): void
    {
        if (! $order || ! $order->id) {
            return;
        }

        // Avoid duplicate entries for the same order
        $exists = FinancialTransaction::where('reference_type', 'order')
            ->where('reference_id', $order->id)
            ->where('type', 'sale')
            ->exists();

        if ($exists) {
            return;
        }

        FinancialTransaction::create([
            'type'             => 'sale',
            'amount'           => (float) $order->grand_total,
            'description'      => 'Order #'.($order->increment_id ?? $order->id),
            'reference_id'     => $order->id,
            'reference_type'   => 'order',
            'transaction_date' => $order->created_at?->toDateString() ?? now()->toDateString(),
        ]);
    }

    public function onRefundSaved($refund): void
    {
        if (! $refund || ! $refund->id) {
            return;
        }

        $exists = FinancialTransaction::where('reference_type', 'refund')
            ->where('reference_id', $refund->id)
            ->where('type', 'refund')
            ->exists();

        if ($exists) {
            return;
        }

        $orderId = $refund->order_id ?? null;
        $orderRef = $orderId ? ' (Order #'.($refund->order?->increment_id ?? $orderId).')' : '';

        FinancialTransaction::create([
            'type'             => 'refund',
            'amount'           => -abs((float) $refund->grand_total),
            'description'      => 'Refund #'.($refund->increment_id ?? $refund->id).$orderRef,
            'reference_id'     => $refund->id,
            'reference_type'   => 'refund',
            'transaction_date' => $refund->created_at?->toDateString() ?? now()->toDateString(),
        ]);
    }
}
