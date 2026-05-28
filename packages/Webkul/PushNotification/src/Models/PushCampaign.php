<?php

namespace Webkul\PushNotification\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PushCampaign extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'body', 'icon', 'url', 'sent_count'];
}
