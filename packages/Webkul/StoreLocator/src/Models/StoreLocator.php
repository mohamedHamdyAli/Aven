<?php

namespace Webkul\StoreLocator\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\StoreLocator\Contracts\StoreLocator as StoreLocatorContract;

class StoreLocator extends Model implements StoreLocatorContract
{
    use HasFactory;

    protected static function newFactory(): \Illuminate\Database\Eloquent\Factories\Factory
    {
        return \Webkul\StoreLocator\Database\Factories\StoreLocatorFactory::new();
    }

    protected $table = 'store_locators';

    protected $fillable = [
        'name',
        'address',
        'latitude',
        'longitude',
        'phone',
        'working_hours',
        'image',
        'status',
    ];

    protected $casts = [
        'working_hours' => 'array',
        'status'        => 'boolean',
        'latitude'      => 'float',
        'longitude'     => 'float',
    ];
}
