<?php

namespace Webkul\Loyalty\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Loyalty\Models\CustomerLoyaltyPoints;

class CustomerLoyaltyPointsFactory extends Factory
{
    protected $model = CustomerLoyaltyPoints::class;

    public function definition(): array
    {
        return [
            'customer_id' => null,
            'balance'     => $this->faker->numberBetween(0, 5000),
        ];
    }
}
