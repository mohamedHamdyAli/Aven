<?php

namespace Webkul\PushNotification\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PushCampaign extends Model
{
    use HasFactory;

    protected static function newFactory(): \Illuminate\Database\Eloquent\Factories\Factory
    {
        return \Webkul\PushNotification\Database\Factories\PushCampaignFactory::new();
    }

    protected $fillable = ['title', 'body', 'icon', 'url', 'sent_count'];
}
