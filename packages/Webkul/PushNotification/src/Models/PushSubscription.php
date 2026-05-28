<?php

namespace Webkul\PushNotification\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PushSubscription extends Model
{
    use HasFactory;

    protected static function newFactory(): \Illuminate\Database\Eloquent\Factories\Factory
    {
        return \Webkul\PushNotification\Database\Factories\PushSubscriptionFactory::new();
    }

    protected $fillable = ['customer_id', 'endpoint', 'p256dh', 'auth'];
}
