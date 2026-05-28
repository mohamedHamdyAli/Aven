<?php

namespace Webkul\CostManagement\Models;

use Illuminate\Database\Eloquent\Model;

class ProfitDistribution extends Model
{
    protected $fillable = ['period_from', 'period_to', 'net_profit', 'total_distributed', 'notes'];

    protected $casts = ['period_from' => 'date', 'period_to' => 'date', 'net_profit' => 'float', 'total_distributed' => 'float'];

    public function items()
    {
        return $this->hasMany(ProfitDistributionItem::class, 'distribution_id');
    }
}
