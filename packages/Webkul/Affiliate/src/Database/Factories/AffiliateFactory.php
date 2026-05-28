<?php

namespace Webkul\Affiliate\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Affiliate\Models\Affiliate;

class AffiliateFactory extends Factory
{
    protected $model = Affiliate::class;

    public function definition(): array
    {
        return [
            'customer_id'     => null,
            'name'            => $this->faker->name(),
            'email'           => $this->faker->unique()->safeEmail(),
            'code'            => strtoupper($this->faker->unique()->bothify('########')),
            'status'          => 'active',
            'commission_rate' => $this->faker->randomFloat(2, 1, 30),
            'total_earned'    => 0,
            'total_paid'      => 0,
            'notes'           => null,
        ];
    }
}
