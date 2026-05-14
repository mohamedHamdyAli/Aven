<?php

namespace Webkul\SocialCommerce\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Webkul\SocialCommerce\Models\SocialChannelPlatform;

class SocialChannelPlatformRepository extends BaseRepository
{
    public function model(): string
    {
        return SocialChannelPlatform::class;
    }

    public function findByChannelAndPlatform(int $channelId, string $platform): ?SocialChannelPlatform
    {
        return $this->findWhere(['channel_id' => $channelId, 'platform' => $platform])->first();
    }

    public function getActiveByPlatform(string $platform)
    {
        return $this->findWhere(['platform' => $platform, 'is_active' => true]);
    }
}
