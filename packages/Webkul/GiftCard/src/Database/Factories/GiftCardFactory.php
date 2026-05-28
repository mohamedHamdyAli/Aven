<?php

namespace Webkul\GiftCard\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\GiftCard\Models\GiftCard;

class GiftCardFactory extends Factory
{
    protected $model = GiftCard::class;

    public function definition(): array
    {
        $balance = $this->faker->randomFloat(2, 50, 500);

        return [
            'code'             => strtoupper(implode('-', str_split($this->faker->unique()->bothify('????-????-????'), 5))),
            'initial_balance'  => $balance,
            'used_amount'      => 0,
            'is_active'        => true,
            'recipient_email'  => $this->faker->safeEmail(),
            'recipient_name'   => $this->faker->name(),
            'expires_at'       => now()->addYear(),
            'message'          => $this->faker->sentence(),
        ];
    }

    public function expired(): static
    {
        return $this->state(['expires_at' => now()->subDay()]);
    }

    public function fullyUsed(): static
    {
        return $this->state(fn (array $attrs) => [
            'used_amount' => $attrs['initial_balance'],
        ]);
    }
}
