<?php

namespace Webkul\Aramex\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AramexApiService
{
    private const BASE_URL      = 'https://ws.aramex.net/ShippingAPI.V2/Shipping/Service_1_0.svc/json';
    private const TRACKING_URL  = 'https://ws.aramex.net/ShippingAPI.V2/Tracking/Service_1_0.svc/json/TrackShipments';

    private function clientInfo(): array
    {
        return [
            'UserName'           => (string) core()->getConfigData('sales.carriers.aramex.api_username'),
            'Password'           => (string) core()->getConfigData('sales.carriers.aramex.api_password'),
            'Version'            => 'v1.0',
            'AccountNumber'      => (string) core()->getConfigData('sales.carriers.aramex.account_number'),
            'AccountPin'         => (string) core()->getConfigData('sales.carriers.aramex.account_pin'),
            'AccountEntity'      => (string) core()->getConfigData('sales.carriers.aramex.account_entity'),
            'AccountCountryCode' => 'EG',
        ];
    }

    /**
     * Create an Aramex shipment.
     *
     * @param  array  $params
     * @return array{success: bool, waybill_number: ?string, aramex_id: ?string, raw: array}
     */
    public function createShipment(array $params): array
    {
        try {
            $response = Http::timeout(20)
                ->post(self::BASE_URL.'/CreateShipments', $params);

            if ($response->successful()) {
                $data      = $response->json();
                $shipments = $data['Shipments'] ?? [];
                $shipment  = is_array($shipments) && count($shipments) > 0 ? $shipments[0] : null;

                $waybill  = $shipment['ID'] ?? $shipment['WaybillNumber'] ?? null;
                $aramexId = $shipment['ID'] ?? null;

                return [
                    'success'        => true,
                    'aramex_id'      => $aramexId,
                    'waybill_number' => $waybill,
                    'raw'            => $data,
                ];
            }

            Log::warning('Aramex createShipment failed', [
                'status'   => $response->status(),
                'response' => $response->body(),
            ]);

            return ['success' => false, 'waybill_number' => null, 'aramex_id' => null, 'raw' => $response->json() ?? []];
        } catch (\Throwable $e) {
            Log::error('Aramex API exception: '.$e->getMessage());

            return ['success' => false, 'waybill_number' => null, 'aramex_id' => null, 'raw' => []];
        }
    }

    /**
     * Track a shipment by Aramex waybill number.
     */
    public function track(string $waybillNumber): ?array
    {
        try {
            $response = Http::timeout(15)->post(self::TRACKING_URL, [
                'ClientInfo'     => $this->clientInfo(),
                'Shipments'      => [$waybillNumber],
                'GetLastTrackingUpdateOnly' => false,
            ]);

            return $response->successful() ? $response->json() : null;
        } catch (\Throwable $e) {
            Log::error('Aramex track exception: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Build the Aramex CreateShipments payload from a Bagisto order.
     */
    public function buildPayloadFromOrder(object $order): array
    {
        $address = $order->shipping_address ?? $order->billing_address;

        $firstName = $address?->first_name ?? 'Customer';
        $lastName  = $address?->last_name  ?? '';
        $phone     = $address?->phone      ?? '';
        $line1     = trim(($address?->address1 ?? '').' '.($address?->address2 ?? ''));
        $cityName  = $address?->city ?? $address?->state ?? 'Cairo';

        $isCod     = strtolower($order->payment?->method ?? '') === 'cashondelivery';
        $cod       = $isCod ? (float) $order->base_grand_total : 0;

        $clientInfo    = $this->clientInfo();
        $accountNumber = $clientInfo['AccountNumber'];

        return [
            'ClientInfo' => $clientInfo,
            'Shipments'  => [
                [
                    'Shipper' => [
                        'Reference1'   => 'Order #'.$order->increment_id,
                        'AccountNumber' => $accountNumber,
                        'PartyAddress' => [
                            'CountryCode' => 'EG',
                            'City'        => 'Cairo',
                            'Line1'       => 'Sender Address',
                        ],
                        'Contact' => [
                            'PersonName'   => 'Store',
                            'PhoneNumber1' => '',
                            'EmailAddress' => '',
                        ],
                    ],
                    'Consignee' => [
                        'Reference1'   => 'Order #'.$order->increment_id,
                        'PartyAddress' => [
                            'CountryCode' => 'EG',
                            'City'        => $cityName,
                            'Line1'       => $line1 ?: '.',
                        ],
                        'Contact' => [
                            'PersonName'   => trim($firstName.' '.$lastName),
                            'PhoneNumber1' => $phone,
                            'EmailAddress' => $address?->email ?? '',
                        ],
                    ],
                    'Details' => [
                        'ActualWeight' => [
                            'Unit'  => 'KG',
                            'Value' => 0.5,
                        ],
                        'NumberOfPieces'       => 1,
                        'DescriptionOfGoods'   => 'Order',
                        'GoodsOriginCountry'   => 'EG',
                        'ProductType'          => 'DOM',
                        'ProductGroup'         => 'DOM',
                        'PaymentType'          => 'P',
                        'PaymentOptions'       => 'CASH',
                        'CashOnDeliveryAmount' => [
                            'Amount'       => $cod,
                            'CurrencyCode' => 'EGP',
                        ],
                    ],
                ],
            ],
            'LabelInfo' => [
                'ReportID'   => 9729,
                'ReportType' => 'URL',
            ],
        ];
    }
}
