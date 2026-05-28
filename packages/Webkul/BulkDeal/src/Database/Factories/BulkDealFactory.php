<?php

namespace Webkul\BulkDeal\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\BulkDeal\Models\BulkDeal;

class BulkDealFactory extends Factory
{
    protected $model = BulkDeal::class;

    public function definition(): array
    {
        return [
            'name'         => $this->faker->words(3, true),
            'description'  => $this->faker->sentence(),
            'status'       => true,
            'paid_quantity' => $this->faker->numberBetween(1, 5),
            'deal_quantity' => $this->faker->numberBetween(6, 20),
            'deal_price'   => $this->faker->randomFloat(2, 50, 500),
            'starts_from'  => now()->subDay(),
            'ends_till'    => now()->addDays(30),
            'sort_order'   => $this->faker->numberBetween(0, 100),
        ];
    }

    public function inactive(): static
    {
        return $this->state(['status' => false]);
    }
}
