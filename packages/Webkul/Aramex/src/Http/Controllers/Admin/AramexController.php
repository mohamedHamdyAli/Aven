<?php

namespace Webkul\Aramex\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Webkul\Aramex\Services\AramexApiService;

class AramexController extends Controller
{
    public function __construct(private AramexApiService $api) {}

    /**
     * List all Aramex deliveries.
     */
    public function index()
    {
        $deliveries = DB::table('aramex_deliveries as ad')
            ->join('orders as o', 'o.id', '=', 'ad.order_id')
            ->select(
                'ad.id',
                'ad.order_id',
                'o.increment_id as order_number',
                'ad.waybill_number',
                'ad.aramex_id',
                'ad.status',
                'ad.created_at',
            )
            ->orderByDesc('ad.id')
            ->paginate(30);

        return view('aramex::admin.index', compact('deliveries'));
    }

    /**
     * Manually create an Aramex shipment for an order.
     */
    public function create(int $orderId): JsonResponse
    {
        $order = DB::table('orders')->where('id', $orderId)->first();

        if (! $order) {
            return new JsonResponse(['message' => 'Order not found.'], 404);
        }

        $orderObj = \Webkul\Sales\Repositories\OrderRepository::find($orderId)
            ?? (object) (array) $order;

        $payload = $this->api->buildPayloadFromOrder($orderObj);
        $result  = $this->api->createShipment($payload);

        if ($result['success']) {
            DB::table('aramex_deliveries')->updateOrInsert(
                ['order_id' => $orderId],
                [
                    'aramex_id'      => $result['aramex_id'],
                    'waybill_number' => $result['waybill_number'],
                    'status'         => 'created',
                    'response'       => json_encode($result['raw']),
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]
            );
        }

        return new JsonResponse([
            'success'        => $result['success'],
            'waybill_number' => $result['waybill_number'],
            'message'        => $result['success'] ? 'Shipment created.' : 'Failed to create shipment.',
        ]);
    }

    /**
     * Refresh tracking status from Aramex API.
     */
    public function track(string $waybill): JsonResponse
    {
        $data = $this->api->track($waybill);

        if (! $data) {
            return new JsonResponse(['message' => 'Could not fetch tracking info.'], 422);
        }

        return new JsonResponse(['data' => $data]);
    }
}
