<?php

namespace Webkul\Shop\Listeners;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FacebookConversionsListener
{
    private const API_URL = 'https://graph.facebook.com/v18.0/%s/events';

    public function onOrderSaved($order): void
    {
        $pixelId = core()->getConfigData('general.content.facebook_pixel.pixel_id');
        $token   = core()->getConfigData('general.content.facebook_pixel.conversions_api_token');

        if (!$pixelId || !$token) {
            return;
        }

        if (!core()->getConfigData('general.content.facebook_pixel.enabled')) {
            return;
        }

        $address  = $order->billing_address ?? $order->shipping_address;
        $email    = $order->customer_email ?? $address?->email ?? null;
        $phone    = $address?->phone ?? null;
        $currency = core()->getCurrentCurrency()->code ?? 'EGP';

        $userData = ['client_ip_address' => request()->ip(), 'client_user_agent' => request()->userAgent()];
        if ($email) $userData['em'] = [hash('sha256', strtolower(trim($email)))];
        if ($phone) $userData['ph'] = [hash('sha256', preg_replace('/[^0-9]/', '', $phone))];

        $payload = [
            'data' => [[
                'event_name'       => 'Purchase',
                'event_time'       => now()->timestamp,
                'action_source'    => 'website',
                'event_source_url' => url('/'),
                'user_data'        => $userData,
                'custom_data'      => [
                    'currency' => $currency,
                    'value'    => (float) $order->grand_total,
                    'order_id' => $order->increment_id,
                ],
            ]],
        ];

        $url = sprintf(self::API_URL, $pixelId) . '?access_token=' . $token;

        app()->terminating(function () use ($url, $payload) {
            try {
                Http::timeout(5)->post($url, $payload);
            } catch (\Throwable $e) {
                Log::warning('Facebook Conversions API error: ' . $e->getMessage());
            }
        });
    }
}
