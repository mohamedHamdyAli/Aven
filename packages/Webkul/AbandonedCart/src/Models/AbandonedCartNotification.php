<?php

namespace Webkul\AbandonedCart\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\AbandonedCart\Contracts\AbandonedCartNotification as AbandonedCartNotificationContract;
use Webkul\Checkout\Models\CartProxy;

class AbandonedCartNotification extends Model implements AbandonedCartNotificationContract
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'cart_id',
        'channel',
        'attempt_number',
        'status',
        'sent_at',
        'opened_at',
        'clicked_at',
        'error_message',
        'created_at',
    ];

    protected $casts = [
        'sent_at'    => 'datetime',
        'opened_at'  => 'datetime',
        'clicked_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function cart()
    {
        return $this->belongsTo(CartProxy::modelClass());
    }
}
