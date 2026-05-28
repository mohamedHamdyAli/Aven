<?php

namespace Webkul\Bosta\Listeners;

use Illuminate\Support\Facades\DB;
use Webkul\Bosta\Services\BostaApiService;

class CreateBostaDelivery
{
    public function __construct(private BostaApiService $api) {}

    public function onOrderSaved($order): void
    {
        if (! core()->getConfigData('sales.carriers.bosta.auto_create_shipment')) {
            return;
        }

        if (($order->shipping_method ?? '') !== 'bosta_bosta') {
            return;
        }

        $already = DB::table('bosta_deliveries')->where('order_id', $order->id)->exists();
        if ($already) {
            return;
        }

        $payload = $this->api->buildPayloadFromOrder($order);
        $result  = $this->api->createDelivery($payload);

        DB::table('bosta_deliveries')->insert([
            'order_id'        => $order->id,
            'bosta_id'        => $result['delivery_id'],
            'tracking_number' => $result['tracking_number'],
            'status'          => $result['success'] ? 'created' : 'failed',
            'response'        => json_encode($result['raw']),
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);
    }
}
