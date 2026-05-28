<?php

namespace Webkul\SizeGuide\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SizeChartRow extends Model
{
    use HasFactory;

    protected static function newFactory(): \Illuminate\Database\Eloquent\Factories\Factory
    {
        return \Webkul\SizeGuide\Database\Factories\SizeChartRowFactory::new();
    }

    protected $fillable = [
        'size_chart_id', 'label', 'sort_order',
        'eu_size', 'uk_size', 'us_size',
        'chest_min', 'chest_max', 'waist_min', 'waist_max',
        'hips_min', 'hips_max', 'height_min', 'height_max',
        'product_chest', 'product_waist', 'product_length', 'product_shoulder',
    ];

    protected $casts = [
        'chest_min' => 'float', 'chest_max' => 'float',
        'waist_min' => 'float', 'waist_max' => 'float',
        'hips_min'  => 'float', 'hips_max'  => 'float',
        'height_min'=> 'float', 'height_max'=> 'float',
        'product_chest' => 'float', 'product_waist' => 'float',
        'product_length'=> 'float', 'product_shoulder'=> 'float',
    ];

    /** Range string e.g. "62-66" or "62" */
    public function range(string $field): ?string
    {
        $min = $this->{$field . '_min'};
        $max = $this->{$field . '_max'};
        if ($min === null) return null;
        return $min == $max || $max === null ? (string)$min : "{$min}-{$max}";
    }

    /** Convert CM value to inches */
    public static function toIn(float $cm): float
    {
        return round($cm / 2.54, 1);
    }
}
