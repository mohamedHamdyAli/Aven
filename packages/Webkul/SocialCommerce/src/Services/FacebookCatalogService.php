<?php

namespace Webkul\SocialCommerce\Services;

use Illuminate\Support\Facades\Http;
use Webkul\SocialCommerce\Models\SocialChannelPlatform;

class FacebookCatalogService
{
    private const API_VERSION = 'v18.0';
    private const BASE_URL    = 'https://graph.facebook.com';

    public function syncProduct(SocialChannelPlatform $platform, $product): string
    {
        $retailerId = (string) $product->id;

        $response = Http::post(
            self::BASE_URL.'/'.self::API_VERSION.'/'.$platform->catalog_id.'/items_batch',
            [
                'requests' => [
                    [
                        'method'      => 'UPDATE',
                        'retailer_id' => $retailerId,
                        'data'        => [
                            'name'         => $product->name,
                            'description'  => strip_tags($product->description ?? ''),
                            'availability' => $product->isSaleable() ? 'in stock' : 'out of stock',
                            'condition'    => 'new',
                            'price'        => (int) ($product->getTypeInstance()->getMinimalPrice() * 100).' '.strtoupper(core()->getCurrentCurrencyCode()),
                            'link'         => route('shop.product_or_category.index', $product->url_key),
                            'image_link'   => $product->base_image->medium_image_url ?? '',
                            'brand'        => $product->brand ?? '',
                            'retailer_id'  => $retailerId,
                        ],
                    ],
                ],
                'access_token' => $platform->access_token,
            ]
        );

        if ($response->failed()) {
            throw new \RuntimeException('Facebook catalog sync failed: '.$response->body());
        }

        return $retailerId;
    }

    public function removeProduct(SocialChannelPlatform $platform, int $productId): void
    {
        Http::post(
            self::BASE_URL.'/'.self::API_VERSION.'/'.$platform->catalog_id.'/items_batch',
            [
                'requests'     => [['method' => 'DELETE', 'retailer_id' => (string) $productId]],
                'access_token' => $platform->access_token,
            ]
        );
    }
}
