<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;

class StockNotification extends Model
{
    protected $fillable = ['product_id', 'email', 'phone', 'channel_id', 'notified'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
