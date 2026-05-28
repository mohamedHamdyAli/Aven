<?php

namespace Webkul\ShopTheLook\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Product\Models\Product;

class ProductLookItem extends Model
{
    use HasFactory;

    protected static function newFactory(): \Illuminate\Database\Eloquent\Factories\Factory
    {
        return \Webkul\ShopTheLook\Database\Factories\ProductLookItemFactory::new();
    }

    public $timestamps = false;

    protected $fillable = ['product_id', 'look_product_id', 'sort_order'];

    public function lookProduct()
    {
        return $this->belongsTo(Product::class, 'look_product_id');
    }
}
