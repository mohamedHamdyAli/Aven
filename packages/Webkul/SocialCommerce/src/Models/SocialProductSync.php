<?php

namespace Webkul\SocialCommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Product\Models\ProductProxy;
use Webkul\SocialCommerce\Contracts\SocialProductSync as SocialProductSyncContract;

class SocialProductSync extends Model implements SocialProductSyncContract
{
    protected $fillable = [
        'social_channel_platform_id',
        'product_id',
        'external_product_id',
        'sync_status',
        'error_message',
        'synced_at',
    ];

    protected $casts = [
        'synced_at' => 'datetime',
    ];

    public function platform(): BelongsTo
    {
        return $this->belongsTo(SocialChannelPlatform::class, 'social_channel_platform_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }
}
