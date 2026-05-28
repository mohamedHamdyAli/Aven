<?php

namespace Webkul\FlashSale\Models;

use Illuminate\Database\Eloquent\Model;

class FlashSale extends Model
{
    protected $fillable = ['name', 'discount_percent', 'starts_at', 'ends_at', 'active'];

    protected $casts = [
        'starts_at'        => 'datetime',
        'ends_at'          => 'datetime',
        'active'           => 'boolean',
        'discount_percent' => 'float',
    ];

    public function products()
    {
        return $this->belongsToMany(\Webkul\Product\Models\Product::class, 'flash_sale_products', 'flash_sale_id', 'product_id');
    }

    public function isRunning(): bool
    {
        return $this->active
            && now()->between($this->starts_at, $this->ends_at);
    }
}
