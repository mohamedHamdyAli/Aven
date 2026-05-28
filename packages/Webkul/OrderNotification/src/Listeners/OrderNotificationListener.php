<?php

namespace Webkul\OrderNotification\Listeners;

use Illuminate\Support\Facades\Log;
use Webkul\AbandonedCart\Services\WhatsAppService;

class OrderNotificationListener
{
    public function __construct(protected WhatsAppService $whatsApp) {}

    public function onOrderPlaced(object $order): void
    {
        if (! core()->getConfigData('sales.order_notification.general.enabled')) return;

        $template = core()->getConfigData('sales.order_notification.general.template_placed') ?: 'order_placed';
        $phone    = $this->resolvePhone($order);
        if (! $phone) return;

        $params = [['type' => 'body', 'parameters' => [
            ['type' => 'text', 'text' => $order->customer_first_name ?? 'Customer'],
            ['type' => 'text', 'text' => $order->increment_id],
            ['type' => 'text', 'text' => core()->formatPrice($order->grand_total)],
        ]]];
        $wa = $this->whatsApp;

        app()->terminating(fn () => $wa->sendTemplate($phone, $template, $params));
    }

    public function onShipmentCreated(object $shipment): void
    {
        if (! core()->getConfigData('sales.order_notification.general.enabled')) return;

        $template = core()->getConfigData('sales.order_notification.general.template_shipped') ?: 'order_shipped';
        $order    = $shipment->order;
        $phone    = $this->resolvePhone($order);
        if (! $phone) return;

        $trackingNumber = $shipment->track_number ?? '';
        $params = [['type' => 'body', 'parameters' => [
            ['type' => 'text', 'text' => $order->customer_first_name ?? 'Customer'],
            ['type' => 'text', 'text' => $order->increment_id],
            ['type' => 'text', 'text' => $trackingNumber ?: 'N/A'],
        ]]];
        $wa = $this->whatsApp;

        app()->terminating(fn () => $wa->sendTemplate($phone, $template, $params));
    }

    public function onStatusUpdated(object $order): void
    {
        if (! core()->getConfigData('sales.order_notification.general.enabled')) return;
        if ($order->status !== 'completed') return;

        $template = core()->getConfigData('sales.order_notification.general.template_delivered') ?: 'order_delivered';
        $phone    = $this->resolvePhone($order);
        if (! $phone) return;

        $params = [['type' => 'body', 'parameters' => [
            ['type' => 'text', 'text' => $order->customer_first_name ?? 'Customer'],
            ['type' => 'text', 'text' => $order->increment_id],
        ]]];
        $wa = $this->whatsApp;

        app()->terminating(fn () => $wa->sendTemplate($phone, $template, $params));
    }

    private function resolvePhone(object $order): ?string
    {
        $phone = $order->billing_address?->phone ?? $order->customer?->phone ?? null;

        if (! $phone) {
            Log::debug('OrderNotification: no phone for order ' . $order->increment_id);
        }

        return $phone ?: null;
    }
}
