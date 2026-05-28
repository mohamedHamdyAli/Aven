<?php

namespace Webkul\CostManagement\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\CostManagement\Models\ProfitDistributionItem;

class ProfitDistributionItemFactory extends Factory
{
    protected $model = ProfitDistributionItem::class;

    public function definition(): array
    {
        return [
            'distribution_id'  => null,
            'shareholder_id'   => null,
            'percentage'       => $this->faker->randomFloat(2, 5, 50),
            'amount'           => $this->faker->randomFloat(2, 100, 10000),
        ];
    }
}
