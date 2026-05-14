<?php

namespace Webkul\SocialCommerce\Services;

use Webkul\SocialCommerce\Models\SocialChannelPlatform;

class CatalogSyncManager
{
    public function __construct(
        protected FacebookCatalogService $facebook,
        protected TikTokCatalogService $tiktok,
        protected GoogleMerchantService $google,
        protected WhatsAppService $whatsapp,
    ) {}

    public function syncProduct(SocialChannelPlatform $platform, $product): string
    {
        return match ($platform->platform) {
            'facebook', 'instagram' => $this->facebook->syncProduct($platform, $product),
            'tiktok'                => $this->tiktok->syncProduct($platform, $product),
            'youtube'               => $this->google->syncProduct($platform, $product),
            'whatsapp'              => $this->whatsapp->syncProduct($platform, $product),
            default                 => throw new \InvalidArgumentException("Unsupported platform: {$platform->platform}"),
        };
    }
}
