<?php

namespace Webkul\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Core\Models\Channel;

class ChannelAdSpend extends Model
{
    protected $table = 'channel_ad_spends';

    protected $fillable = [
        'channel_id',
        'amount',
        'start_date',
        'end_date',
        'source',
        'notes',
    ];

    protected $casts = [
        'amount'     => 'decimal:4',
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }
}
