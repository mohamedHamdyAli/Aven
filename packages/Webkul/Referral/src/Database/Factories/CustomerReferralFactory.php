<?php

namespace Webkul\Referral\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Referral\Models\CustomerReferral;

class CustomerReferralFactory extends Factory
{
    protected $model = CustomerReferral::class;

    public function definition(): array
    {
        return [
            'customer_id'   => null,
            'referral_code' => strtoupper($this->faker->unique()->bothify('REF-#####')),
            'times_used'    => $this->faker->numberBetween(0, 20),
            'total_earned'  => $this->faker->randomFloat(4, 0, 500),
        ];
    }
}
