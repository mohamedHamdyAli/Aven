<?php

namespace Webkul\CostManagement\Models;

use Illuminate\Database\Eloquent\Model;

class Shareholder extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'percentage', 'active', 'notes', 'joined_at'];

    protected $casts = ['active' => 'boolean', 'joined_at' => 'date', 'percentage' => 'float'];

    public function distributionItems()
    {
        return $this->hasMany(ProfitDistributionItem::class);
    }

    public function totalEarned(): float
    {
        return (float) $this->distributionItems()->sum('amount');
    }
}
