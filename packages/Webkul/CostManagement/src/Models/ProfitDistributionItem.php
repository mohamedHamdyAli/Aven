<?php

namespace Webkul\CostManagement\Models;

use Illuminate\Database\Eloquent\Model;

class ProfitDistributionItem extends Model
{
    protected $fillable = ['distribution_id', 'shareholder_id', 'percentage', 'amount'];

    protected $casts = ['percentage' => 'float', 'amount' => 'float'];

    public function shareholder()
    {
        return $this->belongsTo(Shareholder::class);
    }

    public function distribution()
    {
        return $this->belongsTo(ProfitDistribution::class, 'distribution_id');
    }
}
