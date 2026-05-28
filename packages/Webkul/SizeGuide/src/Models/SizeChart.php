<?php

namespace Webkul\SizeGuide\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SizeChart extends Model
{
    use HasFactory;

    protected static function newFactory(): \Illuminate\Database\Eloquent\Factories\Factory
    {
        return \Webkul\SizeGuide\Database\Factories\SizeChartFactory::new();
    }

    protected $fillable = ['name', 'gender', 'type', 'image', 'image_overlays', 'column_headers'];

    protected $casts = [
        'image_overlays'  => 'array',
        'column_headers'  => 'array',
    ];

    public function rows()
    {
        return $this->hasMany(SizeChartRow::class)->orderBy('sort_order');
    }

    public function products()
    {
        return $this->belongsToMany(\Webkul\Product\Models\Product::class, 'product_size_chart');
    }
}
