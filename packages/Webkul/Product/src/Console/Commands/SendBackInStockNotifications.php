<?php

namespace Webkul\Product\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Webkul\AbandonedCart\Services\WhatsAppService;
use Webkul\Product\Mail\BackInStockMail;
use Webkul\Product\Models\StockNotification;

class SendBackInStockNotifications extends Command
{
    protected $signature = 'stock:notify';

    protected $description = 'Send back-in-stock email/WhatsApp notifications to subscribers';

    public function handle(): int
    {
        $productIds = StockNotification::where('notified', false)
            ->distinct()
            ->pluck('product_id');

        if ($productIds->isEmpty()) {
            return 0;
        }

        $inStock = DB::table('product_inventory_indices')
            ->whereIn('product_id', $productIds)
            ->where('qty', '>', 0)
            ->pluck('product_id');

        if ($inStock->isEmpty()) {
            return 0;
        }

        $products = DB::table('product_flat')
            ->whereIn('product_id', $inStock)
            ->where('locale', app()->getLocale())
            ->where('status', 1)
            ->select('product_id', 'name', 'url_key')
            ->get()
            ->keyBy('product_id');

        $notifications = StockNotification::whereIn('product_id', $inStock)
            ->where('notified', false)
            ->get();

        $whatsApp = app(WhatsAppService::class);
        $sent = 0;

        foreach ($notifications as $notification) {
            $product = $products[$notification->product_id] ?? null;

            if (! $product) {
                continue;
            }

            if ($notification->email) {
                Mail::queue(new BackInStockMail($notification, $product));
            }

            if ($notification->phone) {
                $productUrl = url('/').'/'.$product->url_key;
                $storeName = core()->getConfigData('general.store_information.name') ?? config('app.name');

                $whatsApp->sendTemplate(
                    $notification->phone,
                    'back_in_stock',
                    [
                        [
                            'type'       => 'body',
                            'parameters' => [
                                ['type' => 'text', 'text' => $product->name],
                                ['type' => 'text', 'text' => $storeName],
                            ],
                        ],
                        [
                            'type'       => 'button',
                            'sub_type'   => 'url',
                            'index'      => '0',
                            'parameters' => [
                                ['type' => 'text', 'text' => $productUrl],
                            ],
                        ],
                    ]
                );
            }

            $notification->update(['notified' => true]);
            $sent++;
        }

        $this->info("Sent {$sent} back-in-stock notifications.");

        return 0;
    }
}
