<?php

namespace Webkul\Loyalty\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Loyalty\Models\CustomerLoyaltyTransaction;

class CustomerLoyaltyTransactionFactory extends Factory
{
    protected $model = CustomerLoyaltyTransaction::class;

    public function definition(): array
    {
        return [
            'customer_id'   => null,
            'order_id'      => null,
            'type'          => $this->faker->randomElement(['earned', 'redeemed', 'adjusted']),
            'points'        => $this->faker->numberBetween(10, 500),
            'balance_after' => $this->faker->numberBetween(0, 5000),
            'description'   => $this->faker->sentence(),
        ];
    }
}
