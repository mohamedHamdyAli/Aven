<?php

namespace Webkul\SmsNotification\Listeners;

use Webkul\SmsNotification\Services\SmsService;

class SmsOrderListener
{
    public function __construct(private SmsService $sms) {}

    public function onOrderSaved($order): void
    {
        if (! core()->getConfigData('sales.sms_notification.order_placed')) return;

        $phone = $order->shipping_address?->phone ?? $order->billing_address?->phone ?? null;
        if (! $phone) {
            $phone = \DB::table('customers')->where('id', $order->customer_id)->value('phone');
        }
        if (! $phone) return;

        $total   = core()->currency($order->grand_total);
        $message = "Your order #{$order->increment_id} has been placed successfully. Total: {$total}. Thank you for shopping with us!";
        $sms     = $this->sms;

        app()->terminating(fn () => $sms->send($phone, $message));
    }

    public function onShipmentSaved($shipment): void
    {
        if (! core()->getConfigData('sales.sms_notification.order_shipped')) return;

        $order = $shipment->order ?? \DB::table('orders')->where('id', $shipment->order_id)->first();
        if (! $order) return;

        $phone = \DB::table('order_addresses')->where('order_id', $order->id)->where('address_type', 'shipping')->value('phone');
        if (! $phone) return;

        $message = "Great news! Your order #{$order->increment_id} has been shipped and is on its way to you.";
        $sms     = $this->sms;

        app()->terminating(fn () => $sms->send($phone, $message));
    }

    public function onOrderStatusUpdated($order): void
    {
        if (! core()->getConfigData('sales.sms_notification.order_delivered')) return;
        if (($order->status ?? '') !== 'completed') return;

        $phone = \DB::table('order_addresses')->where('order_id', $order->id)->where('address_type', 'shipping')->value('phone');
        if (! $phone) return;

        $message = "Your order #{$order->increment_id} has been delivered. Thank you for your purchase! We hope to see you again.";
        $sms     = $this->sms;

        app()->terminating(fn () => $sms->send($phone, $message));
    }
}
