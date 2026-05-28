<?php

namespace Webkul\CostManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Product\Models\Product;

class ProductCost extends Model
{
    protected $fillable = [
        'product_id',
        'cost_price',
        'manufacturing_fee',
        'shipping_cost_per_unit',
        'other_costs',
        'notes',
    ];

    protected $casts = [
        'cost_price'             => 'float',
        'manufacturing_fee'      => 'float',
        'shipping_cost_per_unit' => 'float',
        'other_costs'            => 'float',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getTotalCostAttribute(): float
    {
        return $this->cost_price + $this->manufacturing_fee + $this->shipping_cost_per_unit + $this->other_costs;
    }
}
