<?php

namespace Webkul\CostManagement\Models;

use Illuminate\Database\Eloquent\Model;

class CapitalContribution extends Model
{
    protected $fillable = ['shareholder_id', 'amount', 'contributed_at', 'type', 'notes'];

    protected $casts = ['amount' => 'float', 'contributed_at' => 'date'];

    public static function types(): array
    {
        return [
            'cash'          => 'Cash',
            'asset'         => 'Asset',
            'loan_repayment'=> 'Loan Repayment',
            'other'         => 'Other',
        ];
    }

    public function shareholder()
    {
        return $this->belongsTo(Shareholder::class);
    }
}
