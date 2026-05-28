<?php

namespace Webkul\FlashSale\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\FlashSale\Models\FlashSale;

class FlashSaleFactory extends Factory
{
    protected $model = FlashSale::class;

    public function definition(): array
    {
        return [
            'name'             => $this->faker->words(3, true) . ' Flash Sale',
            'discount_percent' => $this->faker->randomFloat(2, 5, 50),
            'starts_at'        => now()->subHour(),
            'ends_at'          => now()->addHours(24),
            'active'           => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(['active' => false]);
    }

    public function expired(): static
    {
        return $this->state([
            'starts_at' => now()->subDays(2),
            'ends_at'   => now()->subDay(),
        ]);
    }
}
