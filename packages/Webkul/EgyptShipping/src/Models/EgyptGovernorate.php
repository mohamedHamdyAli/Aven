<?php

namespace Webkul\EgyptShipping\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EgyptGovernorate extends Model
{
    use HasFactory;

    protected static function newFactory(): \Illuminate\Database\Eloquent\Factories\Factory
    {
        return \Webkul\EgyptShipping\Database\Factories\EgyptGovernorateFactory::new();
    }

    protected $table = 'egypt_shipping_governorates';

    protected $fillable = ['code', 'name_ar', 'name_en', 'rate', 'is_active'];

    protected $casts = [
        'rate'      => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
