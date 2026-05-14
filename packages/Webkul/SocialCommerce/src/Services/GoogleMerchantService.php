<?php

namespace Webkul\SocialCommerce\Services;

use Illuminate\Support\Facades\Http;
use Webkul\SocialCommerce\Models\SocialChannelPlatform;

class GoogleMerchantService
{
    private const BASE_URL = 'https://shoppingcontent.googleapis.com/content/v2.1';

    public function syncProduct(SocialChannelPlatform $platform, $product): string
    {
        $offerId    = 'product-'.$product->id;
        $merchantId = $platform->app_id;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$platform->access_token,
            'Content-Type'  => 'application/json',
        ])->post(self::BASE_URL.'/'.$merchantId.'/products', [
            'offerId'         => $offerId,
            'title'           => $product->name,
            'description'     => strip_tags($product->description ?? ''),
            'link'            => route('shop.product_or_category.index', $product->url_key),
            'imageLink'       => $product->base_image->medium_image_url ?? '',
            'contentLanguage' => 'en',
            'targetCountry'   => 'US',
            'channel'         => 'online',
            'availability'    => 'in stock',
            'condition'       => 'new',
            'price'           => [
                'value'    => (string) $product->getTypeInstance()->getMinimalPrice(),
                'currency' => strtoupper(core()->getCurrentCurrencyCode()),
            ],
        ]);

        if ($response->failed()) {
            throw new \RuntimeException('Google Merchant sync failed: '.$response->body());
        }

        return $offerId;
    }
}
