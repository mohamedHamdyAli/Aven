<?php

namespace Webkul\SocialCommerce\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Webkul\SocialCommerce\Models\SocialProductSync;

class SocialProductSyncRepository extends BaseRepository
{
    public function model(): string
    {
        return SocialProductSync::class;
    }

    public function updateOrCreateSync(int $platformId, int $productId, array $data): SocialProductSync
    {
        return $this->updateOrCreate(
            ['social_channel_platform_id' => $platformId, 'product_id' => $productId],
            $data
        );
    }
}
