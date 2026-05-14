<?php

namespace Webkul\SocialCommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Sales\Models\OrderProxy;
use Webkul\SocialCommerce\Contracts\SocialOrder as SocialOrderContract;

class SocialOrder extends Model implements SocialOrderContract
{
    protected $fillable = [
        'social_channel_platform_id',
        'order_id',
        'external_order_id',
        'platform_data',
        'sync_status',
        'error_message',
    ];

    protected $casts = [
        'platform_data' => 'array',
    ];

    public function platform(): BelongsTo
    {
        return $this->belongsTo(SocialChannelPlatform::class, 'social_channel_platform_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(OrderProxy::modelClass());
    }
}
