<?php

namespace Webkul\AiSupport\Services;

use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\ShipmentRepository;

class OrderContextService
{
    public function __construct(
        protected OrderRepository $orderRepository,
        protected ShipmentRepository $shipmentRepository
    ) {}

    public function getOrderStatus(string $orderId): string
    {
        try {
            $order = $this->orderRepository->findWhere(['increment_id' => $orderId])->first()
                ?? $this->orderRepository->find((int) $orderId);

            if (! $order) {
                return "Order #{$orderId} was not found. Please check the order number and try again.";
            }

            $items = $order->items->map(fn ($item) => "- {$item->name} (qty: {$item->qty_ordered})")->implode("\n");

            return <<<INFO
Order #{$order->increment_id}
Status: {$order->status}
Date: {$order->created_at->format('Y-m-d')}
Total: {$order->base_currency} {$order->base_grand_total}
Items:
{$items}
INFO;
        } catch (\Throwable $e) {
            return "Could not retrieve order details for #{$orderId}.";
        }
    }

    public function getShippingStatus(string $orderId): string
    {
        try {
            $order = $this->orderRepository->findWhere(['increment_id' => $orderId])->first()
                ?? $this->orderRepository->find((int) $orderId);

            if (! $order) {
                return "Order #{$orderId} was not found.";
            }

            $shipments = $this->shipmentRepository->findWhere(['order_id' => $order->id]);

            if ($shipments->isEmpty()) {
                return "Order #{$order->increment_id} has not been shipped yet. Current status: {$order->status}.";
            }

            $result = "Shipping info for Order #{$order->increment_id}:\n";
            foreach ($shipments as $shipment) {
                $result .= "Shipment #{$shipment->id} — Tracking: ".($shipment->track_number ?? 'N/A')."\n";
            }

            return $result;
        } catch (\Throwable $e) {
            return "Could not retrieve shipping details for #{$orderId}.";
        }
    }
}
