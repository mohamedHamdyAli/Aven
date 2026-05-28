<?php

namespace Webkul\Bosta\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Webkul\Bosta\Services\BostaApiService;

class BostaController extends Controller
{
    public function __construct(private BostaApiService $api) {}

    /**
     * List all Bosta deliveries.
     */
    public function index()
    {
        $deliveries = DB::table('bosta_deliveries as bd')
            ->join('orders as o', 'o.id', '=', 'bd.order_id')
            ->select(
                'bd.id',
                'bd.order_id',
                'o.increment_id as order_number',
                'bd.tracking_number',
                'bd.bosta_id',
                'bd.status',
                'bd.created_at',
            )
            ->orderByDesc('bd.id')
            ->paginate(30);

        return view('bosta::admin.index', compact('deliveries'));
    }

    /**
     * Manually create a Bosta shipment for an order.
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
        $result  = $this->api->createDelivery($payload);

        if ($result['success']) {
            DB::table('bosta_deliveries')->updateOrInsert(
                ['order_id' => $orderId],
                [
                    'bosta_id'        => $result['delivery_id'],
                    'tracking_number' => $result['tracking_number'],
                    'status'          => 'created',
                    'response'        => json_encode($result['raw']),
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]
            );
        }

        return new JsonResponse([
            'success'         => $result['success'],
            'tracking_number' => $result['tracking_number'],
            'message'         => $result['success'] ? 'Shipment created.' : 'Failed to create shipment.',
        ]);
    }

    /**
     * Refresh tracking status from Bosta API.
     */
    public function track(string $trackingNumber): JsonResponse
    {
        $data = $this->api->track($trackingNumber);

        if (! $data) {
            return new JsonResponse(['message' => 'Could not fetch tracking info.'], 422);
        }

        return new JsonResponse(['data' => $data]);
    }
}
