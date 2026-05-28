<?php

namespace Webkul\Affiliate\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateClick extends Model
{
    use HasFactory;

    protected static function newFactory(): \Illuminate\Database\Eloquent\Factories\Factory
    {
        return \Webkul\Affiliate\Database\Factories\AffiliateClickFactory::new();
    }

    protected $fillable = ['affiliate_id', 'ip', 'url'];
}
