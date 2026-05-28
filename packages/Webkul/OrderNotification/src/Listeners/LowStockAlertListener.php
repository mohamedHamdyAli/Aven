<?php

namespace Webkul\OrderNotification\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Webkul\AbandonedCart\Services\WhatsAppService;

class LowStockAlertListener
{
    public function __construct(protected WhatsAppService $whatsApp) {}

    public function onProductUpdated(object $product): void
    {
        $threshold = (int) core()->getConfigData('sales.order_notification.low_stock.threshold');

        if ($threshold <= 0) {
            return;
        }

        $totalQty = $product->inventories()->sum('qty');

        if ($totalQty > $threshold || $totalQty < 0) {
            return;
        }

        // Avoid spamming — only alert once per crossing the threshold
        $cacheKey = "low_stock_alerted_{$product->id}_{$totalQty}";
        if (cache()->has($cacheKey)) {
            return;
        }

        cache()->put($cacheKey, true, now()->addHours(6));

        $adminEmail = core()->getConfigData('emails.general.notifications.emails.general.notifications.sender-email')
            ?? config('mail.from.address');

        $productName = $product->product_flat?->first()?->name ?? $product->sku;

        // Email alert
        if ($adminEmail) {
            try {
                Mail::raw(
                    "⚠️ Low Stock Alert\n\nProduct: {$productName}\nSKU: {$product->sku}\nRemaining Qty: {$totalQty}\n\nPlease restock soon.",
                    fn ($msg) => $msg->to($adminEmail)->subject("Low Stock: {$productName} ({$totalQty} left)")
                );
            } catch (\Throwable $e) {
                Log::warning("Low stock email failed for [{$product->sku}]: ".$e->getMessage());
            }
        }

        // WhatsApp alert to admin
        $adminPhone = core()->getConfigData('sales.order_notification.low_stock.admin_phone');
        $template   = core()->getConfigData('sales.order_notification.low_stock.template') ?: 'low_stock_alert';

        if ($adminPhone) {
            try {
                $this->whatsApp->sendTemplate($adminPhone, $template, [
                    [
                        'type'       => 'body',
                        'parameters' => [
                            ['type' => 'text', 'text' => $productName],
                            ['type' => 'text', 'text' => (string) $totalQty],
                        ],
                    ],
                ]);
            } catch (\Throwable $e) {
                Log::warning("Low stock WhatsApp failed for [{$product->sku}]: ".$e->getMessage());
            }
        }

        Log::info("Low stock alert fired for product [{$product->sku}] — qty: {$totalQty}");
    }
}
