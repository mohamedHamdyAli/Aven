<?php

namespace Webkul\EgyptShipping\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EgyptGovernorate extends Model
{
    use HasFactory;
    protected $table = 'egypt_shipping_governorates';

    protected $fillable = ['code', 'name_ar', 'name_en', 'rate', 'is_active'];

    protected $casts = [
        'rate'      => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
