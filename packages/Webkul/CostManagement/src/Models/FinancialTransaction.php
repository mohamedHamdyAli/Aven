<?php

namespace Webkul\CostManagement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialTransaction extends Model
{
    use HasFactory;

    protected static function newFactory(): \Illuminate\Database\Eloquent\Factories\Factory
    {
        return \Webkul\CostManagement\Database\Factories\FinancialTransactionFactory::new();
    }

    protected $fillable = [
        'type',
        'amount',
        'description',
        'platform',
        'reference_id',
        'reference_type',
        'transaction_date',
    ];

    protected $casts = [
        'amount'           => 'float',
        'transaction_date' => 'date',
    ];

    public static function typeLabels(): array
    {
        return [
            'sale'      => 'Sale',
            'refund'    => 'Refund',
            'expense'   => 'Expense',
            'ad_spend'  => 'Ad Spend',
        ];
    }

    public static function platforms(): array
    {
        return ['Facebook', 'Instagram', 'Google', 'TikTok', 'Snapchat', 'Twitter/X', 'Other'];
    }
}
