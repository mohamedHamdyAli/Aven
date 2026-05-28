<?php

namespace Webkul\Affiliate\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateClick extends Model
{
    protected $fillable = ['affiliate_id', 'ip', 'url'];
}
