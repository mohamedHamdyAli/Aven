<?php

namespace Webkul\CostManagement\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\CostManagement\Models\ProductCost;

class ProductCostFactory extends Factory
{
    protected $model = ProductCost::class;

    public function definition(): array
    {
        return [
            'product_id'             => null,
            'cost_price'             => $this->faker->randomFloat(2, 10, 300),
            'manufacturing_fee'      => $this->faker->randomFloat(2, 0, 50),
            'shipping_cost_per_unit' => $this->faker->randomFloat(2, 0, 30),
            'other_costs'            => $this->faker->randomFloat(2, 0, 20),
            'notes'                  => $this->faker->sentence(),
        ];
    }
}
