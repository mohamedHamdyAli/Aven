<?php

namespace Webkul\Paymob\Payment;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Webkul\Payment\Payment\Payment;

class Paymob extends Payment
{
    protected $code = 'paymob';

    const BASE_URL = 'https://accept.paymob.com/api';

    public function getRedirectUrl(): string
    {
        return route('paymob.redirect');
    }

    public function isAvailable(): bool
    {
        return (bool) $this->getConfigData('active')
            && $this->getConfigData('api_key')
            && $this->getConfigData('integration_id')
            && $this->getConfigData('hmac_secret')
            && $this->getConfigData('iframe_id');
    }

    public function getTitle(): string
    {
        return $this->getConfigData('title') ?? 'Pay with Card (Paymob)';
    }

    public function getImage()
    {
        $url = $this->getConfigData('image');

        return $url ? Storage::url($url) : '';
    }

    public function getAuthToken(): string
    {
        $response = Http::post(self::BASE_URL . '/auth/tokens', [
            'api_key' => $this->getConfigData('api_key'),
        ]);

        return $response->json('token');
    }

    public function createOrder(string $authToken, $cart): array
    {
        $response = Http::post(self::BASE_URL . '/ecommerce/orders', [
            'auth_token'     => $authToken,
            'delivery_needed' => false,
            'amount_cents'   => (int) round($cart->grand_total * 100),
            'currency'       => strtoupper($cart->cart_currency_code ?? 'EGP'),
            'merchant_order_id' => $cart->id,
            'items'          => [],
        ]);

        return $response->json();
    }

    public function getPaymentKey(string $authToken, int $paymobOrderId, $cart): string
    {
        $billing = $cart->billing_address;

        $response = Http::post(self::BASE_URL . '/acceptance/payment_keys', [
            'auth_token'     => $authToken,
            'amount_cents'   => (int) round($cart->grand_total * 100),
            'expiration'     => 3600,
            'order_id'       => $paymobOrderId,
            'billing_data'   => [
                'first_name'    => $billing?->first_name ?? 'Guest',
                'last_name'     => $billing?->last_name  ?? 'Customer',
                'email'         => $billing?->email ?? $cart->customer_email ?? 'NA',
                'phone_number'  => $billing?->phone ?? '01000000000',
                'apartment'     => 'NA',
                'floor'         => 'NA',
                'street'        => $billing?->address1 ?? 'NA',
                'building'      => 'NA',
                'shipping_method' => 'NA',
                'postal_code'   => 'NA',
                'city'          => $billing?->city ?? 'NA',
                'country'       => $billing?->country ?? 'EG',
                'state'         => $billing?->state ?? 'NA',
            ],
            'currency'       => strtoupper($cart->cart_currency_code ?? 'EGP'),
            'integration_id' => (int) $this->getConfigData('integration_id'),
            'lock_order_when_paid' => true,
        ]);

        return $response->json('token');
    }

    public function verifyHmac(array $data): bool
    {
        $hmacSecret = $this->getConfigData('hmac_secret');
        $fields     = [
            'amount_cents', 'created_at', 'currency', 'error_occured',
            'has_parent_transaction', 'id', 'integration_id', 'is_3d_secure',
            'is_auth', 'is_capture', 'is_refunded', 'is_standalone_payment',
            'is_voided', 'order.id', 'owner', 'pending', 'source_data.pan',
            'source_data.sub_type', 'source_data.type', 'success',
        ];

        $concatenated = '';

        foreach ($fields as $field) {
            $keys   = explode('.', $field);
            $value  = $data;

            foreach ($keys as $key) {
                $value = $value[$key] ?? '';
            }

            $concatenated .= $value;
        }

        $expected = hash_hmac('sha512', $concatenated, $hmacSecret);

        return hash_equals($expected, $data['hmac'] ?? '');
    }

    public function iframeUrl(string $paymentToken): string
    {
        $iframeId = $this->getConfigData('iframe_id');

        return "https://accept.paymob.com/api/acceptance/iframes/{$iframeId}?payment_token={$paymentToken}";
    }
}
