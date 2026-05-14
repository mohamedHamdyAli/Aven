<?php

namespace Webkul\ShopTheLook\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Product\Models\Product;

class ProductLookItem extends Model
{
    public $timestamps = false;

    protected $fillable = ['product_id', 'look_product_id', 'sort_order'];

    public function lookProduct()
    {
        return $this->belongsTo(Product::class, 'look_product_id');
    }
}
