<?php

namespace Webkul\BulkDeal\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\BulkDeal\Contracts\BulkDeal as BulkDealContract;

class BulkDeal extends Model implements BulkDealContract
{
    protected $table = 'bulk_deals';

    protected $fillable = [
        'name',
        'description',
        'status',
        'paid_quantity',
        'deal_quantity',
        'deal_price',
        'starts_from',
        'ends_till',
        'sort_order',
    ];

    protected $casts = [
        'status'     => 'boolean',
        'deal_price' => 'float',
        'starts_from' => 'datetime',
        'ends_till'   => 'datetime',
    ];
}
