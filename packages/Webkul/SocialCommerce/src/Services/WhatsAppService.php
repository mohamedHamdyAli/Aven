<?php

namespace Webkul\SocialCommerce\Services;

use Illuminate\Support\Facades\Http;
use Webkul\SocialCommerce\Models\SocialChannelPlatform;

class WhatsAppService
{
    private const BASE_URL    = 'https://graph.facebook.com';
    private const API_VERSION = 'v18.0';

    public function sendOrderConfirmation(SocialChannelPlatform $platform, array $order, string $customerPhone): void
    {
        Http::withHeaders([
            'Authorization' => 'Bearer '.$platform->access_token,
            'Content-Type'  => 'application/json',
        ])->post(
            self::BASE_URL.'/'.self::API_VERSION.'/'.$platform->phone_number_id.'/messages',
            [
                'messaging_product' => 'whatsapp',
                'to'                => $customerPhone,
                'type'              => 'template',
                'template'          => [
                    'name'     => 'order_confirmation',
                    'language' => ['code' => 'en_US'],
                    'components' => [
                        [
                            'type'       => 'body',
                            'parameters' => [
                                ['type' => 'text', 'text' => $order['increment_id'] ?? ''],
                                ['type' => 'text', 'text' => (string) ($order['grand_total'] ?? '')],
                            ],
                        ],
                    ],
                ],
            ]
        );
    }

    public function syncProduct(SocialChannelPlatform $platform, $product): string
    {
        // WhatsApp uses the Meta Catalog API — same as Facebook
        $service = app(FacebookCatalogService::class);

        return $service->syncProduct($platform, $product);
    }
}
