<?php

namespace Webkul\PushNotification\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PushSubscription extends Model
{
    use HasFactory;
    protected $fillable = ['customer_id', 'endpoint', 'p256dh', 'auth'];
}
