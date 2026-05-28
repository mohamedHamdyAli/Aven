<?php

namespace Webkul\CostManagement\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\CostManagement\Models\Shareholder;

class ShareholderFactory extends Factory
{
    protected $model = Shareholder::class;

    public function definition(): array
    {
        return [
            'name'       => $this->faker->name(),
            'email'      => $this->faker->unique()->safeEmail(),
            'phone'      => $this->faker->phoneNumber(),
            'percentage' => $this->faker->randomFloat(2, 5, 50),
            'active'     => true,
            'notes'      => null,
            'joined_at'  => $this->faker->date('Y-m-d', '-2 years'),
        ];
    }

    public function inactive(): static
    {
        return $this->state(['active' => false]);
    }
}
