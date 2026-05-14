<?php

namespace Webkul\SocialCommerce\Services;

use Illuminate\Support\Facades\Http;
use Webkul\SocialCommerce\Models\SocialChannelPlatform;

class TikTokCatalogService
{
    private const BASE_URL = 'https://business-api.tiktok.com/open_api/v1.3';

    public function syncProduct(SocialChannelPlatform $platform, $product): string
    {
        $skuId = (string) $product->id;

        $response = Http::withHeaders([
            'Access-Token' => $platform->access_token,
            'Content-Type' => 'application/json',
        ])->post(self::BASE_URL.'/catalog/product/batch/', [
            'bc_id'      => $platform->app_id,
            'catalog_id' => $platform->catalog_id,
            'products'   => [
                [
                    'sku_id'      => $skuId,
                    'title'       => $product->name,
                    'description' => strip_tags($product->description ?? ''),
                    'price'       => [
                        'amount'   => (string) $product->getTypeInstance()->getMinimalPrice(),
                        'currency' => strtoupper(core()->getCurrentCurrencyCode()),
                    ],
                    'inventory'   => ['quantity' => 9999],
                    'url'         => route('shop.product_or_category.index', $product->url_key),
                    'image_urls'  => array_filter([$product->base_image->medium_image_url ?? null]),
                ],
            ],
        ]);

        if ($response->json('code') !== 0) {
            throw new \RuntimeException('TikTok catalog sync failed: '.$response->body());
        }

        return $skuId;
    }
}
