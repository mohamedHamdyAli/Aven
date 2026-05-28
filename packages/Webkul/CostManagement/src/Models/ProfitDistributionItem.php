<?php

namespace Webkul\CostManagement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfitDistributionItem extends Model
{
    use HasFactory;

    protected static function newFactory(): \Illuminate\Database\Eloquent\Factories\Factory
    {
        return \Webkul\CostManagement\Database\Factories\ProfitDistributionItemFactory::new();
    }

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
