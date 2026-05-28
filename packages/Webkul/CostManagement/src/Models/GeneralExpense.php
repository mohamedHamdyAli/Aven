<?php

namespace Webkul\CostManagement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralExpense extends Model
{
    use HasFactory;

    protected static function newFactory(): \Illuminate\Database\Eloquent\Factories\Factory
    {
        return \Webkul\CostManagement\Database\Factories\GeneralExpenseFactory::new();
    }

    protected $fillable = [
        'title',
        'category',
        'amount',
        'expense_date',
        'is_recurring',
        'frequency',
        'notes',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'is_recurring' => 'boolean',
        'amount'       => 'float',
    ];

    public static function categories(): array
    {
        return [
            'rent'       => 'Rent / Office',
            'salaries'   => 'Salaries',
            'marketing'  => 'Marketing',
            'utilities'  => 'Utilities',
            'shipping'   => 'Shipping & Logistics',
            'software'   => 'Software & Tools',
            'other'      => 'Other',
        ];
    }
}
