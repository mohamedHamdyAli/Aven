<?php

namespace Webkul\Referral\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Referral\Models\ReferralConversion;

class ReferralConversionFactory extends Factory
{
    protected $model = ReferralConversion::class;

    public function definition(): array
    {
        return [
            'referral_code'          => strtoupper($this->faker->bothify('REF-#####')),
            'referrer_customer_id'   => null,
            'referred_customer_id'   => null,
            'referred_email'         => $this->faker->safeEmail(),
            'order_id'               => null,
            'status'                 => 'pending',
            'rewarded_at'            => null,
        ];
    }

    public function rewarded(): static
    {
        return $this->state(['status' => 'rewarded', 'rewarded_at' => now()]);
    }
}
