<?php

namespace Webkul\Bosta\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BostaApiService
{
    private const BASE_URL = 'https://app.bosta.co/api/v2';

    private function apiKey(): string
    {
        return (string) core()->getConfigData('sales.carriers.bosta.api_key');
    }

    private function client()
    {
        return Http::withHeaders([
            'Authorization' => $this->apiKey(),
            'Content-Type'  => 'application/json',
        ])->timeout(20)->baseUrl(self::BASE_URL);
    }

    /**
     * Create a Bosta delivery.
     *
     * @param  array  $params
     * @return array{success: bool, tracking_number: ?string, delivery_id: ?string, raw: array}
     */
    public function createDelivery(array $params): array
    {
        try {
            $response = $this->client()->post('/deliveries', $params);

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'success'         => true,
                    'delivery_id'     => $data['_id'] ?? null,
                    'tracking_number' => $data['TrackingNumber'] ?? $data['trackingNumber'] ?? null,
                    'raw'             => $data,
                ];
            }

            Log::warning('Bosta createDelivery failed', [
                'status'   => $response->status(),
                'response' => $response->body(),
            ]);

            return ['success' => false, 'tracking_number' => null, 'delivery_id' => null, 'raw' => $response->json() ?? []];
        } catch (\Throwable $e) {
            Log::error('Bosta API exception: '.$e->getMessage());

            return ['success' => false, 'tracking_number' => null, 'delivery_id' => null, 'raw' => []];
        }
    }

    /**
     * Track a delivery by Bosta tracking number (public endpoint).
     */
    public function track(string $trackingNumber): ?array
    {
        try {
            $response = Http::timeout(15)
                ->get(self::BASE_URL.'/tracking/'.$trackingNumber);

            return $response->successful() ? $response->json() : null;
        } catch (\Throwable $e) {
            Log::error('Bosta track exception: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Build the Bosta delivery payload from a Bagisto order.
     */
    public function buildPayloadFromOrder(object $order): array
    {
        $address = $order->shipping_address ?? $order->billing_address;

        $firstName = $address?->first_name ?? 'Customer';
        $lastName  = $address?->last_name  ?? '';
        $phone     = $address?->phone      ?? '';
        $line1     = trim(($address?->address1 ?? '').' '.($address?->address2 ?? ''));
        $cityName  = $address?->city ?? $address?->state ?? 'Cairo';

        $isCod = strtolower($order->payment?->method ?? '') === 'cashondelivery';
        $cod   = $isCod ? (float) $order->base_grand_total : 0;

        return [
            'type' => 10, // SEND
            'specs' => [
                'packageDetails' => [
                    'itemsCount' => (int) $order->total_qty_ordered,
                ],
            ],
            'cod'      => $cod,
            'receiver' => [
                'firstName' => $firstName,
                'lastName'  => $lastName,
                'phone'     => $phone,
                'email'     => $address?->email ?? '',
            ],
            'dropOffAddress' => [
                'firstLine' => $line1 ?: '.',
                'city'      => ['name' => $cityName],
            ],
            'notes' => [
                'description' => 'Order #'.$order->increment_id,
            ],
        ];
    }
}
