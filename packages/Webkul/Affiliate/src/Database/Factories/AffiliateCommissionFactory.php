<?php

namespace Webkul\Affiliate\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Affiliate\Models\AffiliateCommission;

class AffiliateCommissionFactory extends Factory
{
    protected $model = AffiliateCommission::class;

    public function definition(): array
    {
        return [
            'affiliate_id' => AffiliateFactory::new()->create()->id,
            'order_id'     => null,
            'order_total'  => $this->faker->randomFloat(4, 50, 1000),
            'commission'   => $this->faker->randomFloat(4, 1, 50),
            'status'       => $this->faker->randomElement(['pending', 'approved', 'paid']),
        ];
    }

    public function approved(): static
    {
        return $this->state(['status' => 'approved']);
    }
}
