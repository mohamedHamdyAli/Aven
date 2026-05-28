<?php

namespace Webkul\EgyptShipping\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\EgyptShipping\Models\EgyptGovernorate;

class EgyptGovernorateFactory extends Factory
{
    protected $model = EgyptGovernorate::class;

    public function definition(): array
    {
        return [
            'code'      => strtoupper($this->faker->unique()->lexify('???')),
            'name_ar'   => $this->faker->word(),
            'name_en'   => $this->faker->city(),
            'rate'      => $this->faker->randomFloat(2, 20, 100),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(['is_active' => false]);
    }
}
