<?php

namespace Webkul\CostManagement\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\CostManagement\Models\ProfitDistribution;

class ProfitDistributionFactory extends Factory
{
    protected $model = ProfitDistribution::class;

    public function definition(): array
    {
        $from = $this->faker->date('Y-m-d', '-90 days');

        return [
            'period_from'       => $from,
            'period_to'         => $this->faker->date('Y-m-d'),
            'net_profit'        => $this->faker->randomFloat(2, 1000, 50000),
            'total_distributed' => $this->faker->randomFloat(2, 500, 40000),
            'notes'             => $this->faker->sentence(),
        ];
    }
}
