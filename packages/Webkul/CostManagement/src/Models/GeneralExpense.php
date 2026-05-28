<?php

namespace Webkul\CostManagement\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralExpense extends Model
{
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
