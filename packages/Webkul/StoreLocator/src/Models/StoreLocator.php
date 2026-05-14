<?php

namespace Webkul\StoreLocator\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\StoreLocator\Contracts\StoreLocator as StoreLocatorContract;

class StoreLocator extends Model implements StoreLocatorContract
{
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
