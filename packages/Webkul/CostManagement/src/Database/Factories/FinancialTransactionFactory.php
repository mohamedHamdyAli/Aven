<?php

namespace Webkul\CostManagement\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\CostManagement\Models\FinancialTransaction;

class FinancialTransactionFactory extends Factory
{
    protected $model = FinancialTransaction::class;

    public function definition(): array
    {
        return [
            'type'             => $this->faker->randomElement(['sale', 'refund', 'expense', 'ad_spend']),
            'amount'           => $this->faker->randomFloat(2, 10, 5000),
            'description'      => $this->faker->sentence(),
            'platform'         => $this->faker->randomElement(['Facebook', 'Instagram', 'Google', 'TikTok', 'Other']),
            'reference_id'     => null,
            'reference_type'   => null,
            'transaction_date' => $this->faker->date('Y-m-d'),
        ];
    }
}
