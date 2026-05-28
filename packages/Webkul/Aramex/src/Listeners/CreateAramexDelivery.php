<?php

namespace Webkul\Aramex\Listeners;

use Illuminate\Support\Facades\DB;
use Webkul\Aramex\Services\AramexApiService;

class CreateAramexDelivery
{
    public function __construct(private AramexApiService $api) {}

    public function onOrderSaved($order): void
    {
        if (! core()->getConfigData('sales.carriers.aramex.auto_create_shipment')) {
            return;
        }

        if (($order->shipping_method ?? '') !== 'aramex_aramex') {
            return;
        }

        $already = DB::table('aramex_deliveries')->where('order_id', $order->id)->exists();
        if ($already) {
            return;
        }

        $payload = $this->api->buildPayloadFromOrder($order);
        $result  = $this->api->createShipment($payload);

        DB::table('aramex_deliveries')->insert([
            'order_id'       => $order->id,
            'aramex_id'      => $result['aramex_id'],
            'waybill_number' => $result['waybill_number'],
            'status'         => $result['success'] ? 'created' : 'failed',
            'response'       => json_encode($result['raw']),
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }
}
