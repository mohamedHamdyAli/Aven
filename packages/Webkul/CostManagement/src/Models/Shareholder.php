<?php

namespace Webkul\CostManagement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shareholder extends Model
{
    use HasFactory;

    protected static function newFactory(): \Illuminate\Database\Eloquent\Factories\Factory
    {
        return \Webkul\CostManagement\Database\Factories\ShareholderFactory::new();
    }

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
