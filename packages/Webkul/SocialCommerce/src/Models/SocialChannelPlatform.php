<?php

namespace Webkul\SocialCommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\Core\Models\ChannelProxy;
use Webkul\SocialCommerce\Contracts\SocialChannelPlatform as SocialChannelPlatformContract;

class SocialChannelPlatform extends Model implements SocialChannelPlatformContract
{
    protected $fillable = [
        'channel_id',
        'platform',
        'is_active',
        'page_url',
        'page_id',
        'pixel_id',
        'app_id',
        'app_secret',
        'access_token',
        'catalog_id',
        'phone_number_id',
        'last_synced_at',
    ];

    protected $casts = [
        'is_active'      => 'boolean',
        'last_synced_at' => 'datetime',
        'app_id'         => 'encrypted',
        'app_secret'     => 'encrypted',
        'access_token'   => 'encrypted',
    ];

    public function channel(): BelongsTo
    {
        return $this->belongsTo(ChannelProxy::modelClass());
    }

    public function productSyncs(): HasMany
    {
        return $this->hasMany(SocialProductSync::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(SocialOrder::class);
    }
}
