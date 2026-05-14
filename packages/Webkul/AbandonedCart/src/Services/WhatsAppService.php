<?php

namespace Webkul\AbandonedCart\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private const API_BASE = 'https://graph.facebook.com/v18.0';

    /**
     * Send a pre-approved WhatsApp Business template message.
     *
     * @param  string  $to       Recipient phone number in E.164 format (e.g. +201012345678)
     * @param  string  $templateName  Approved template name
     * @param  array   $components    Template components (header/body variable substitutions)
     * @param  string  $languageCode  Language code (default: en_US)
     */
    public function sendTemplate(
        string $to,
        string $templateName,
        array $components = [],
        string $languageCode = 'en_US'
    ): bool {
        $phoneNumberId = core()->getConfigData('sales.abandoned_cart.channels.whatsapp_from_number');
        $token = core()->getConfigData('sales.abandoned_cart.channels.whatsapp_api_token');

        if (! $phoneNumberId || ! $token) {
            return false;
        }

        $to = preg_replace('/[^0-9+]/', '', $to);

        try {
            $response = Http::withToken($token)
                ->timeout(15)
                ->post(self::API_BASE."/{$phoneNumberId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to'                => $to,
                    'type'              => 'template',
                    'template'          => [
                        'name'       => $templateName,
                        'language'   => ['code' => $languageCode],
                        'components' => $components,
                    ],
                ]);

            if ($response->failed()) {
                Log::warning('WhatsApp API error', [
                    'status'   => $response->status(),
                    'response' => $response->json(),
                    'to'       => substr($to, 0, 6).'***',
                ]);

                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('WhatsApp send exception: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Build standard abandoned-cart template components from a cart.
     */
    public function buildAbandonedCartComponents(object $cart): array
    {
        $storeName = core()->getConfigData('general.store_information.name') ?? config('app.name');
        $recoveryUrl = route('shop.abandoned-cart.recover', ['token' => $cart->notification_token]);

        return [
            [
                'type'       => 'body',
                'parameters' => [
                    ['type' => 'text', 'text' => $cart->customer_first_name ?? 'there'],
                    ['type' => 'text', 'text' => $storeName],
                ],
            ],
            [
                'type'    => 'button',
                'sub_type' => 'url',
                'index'   => '0',
                'parameters' => [
                    ['type' => 'text', 'text' => $recoveryUrl],
                ],
            ],
        ];
    }
}
