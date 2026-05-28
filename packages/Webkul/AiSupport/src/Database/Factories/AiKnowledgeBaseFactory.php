<?php

namespace Webkul\AiSupport\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\AiSupport\Models\AiKnowledgeBase;

class AiKnowledgeBaseFactory extends Factory
{
    protected $model = AiKnowledgeBase::class;

    public function definition(): array
    {
        return [
            'question'   => $this->faker->sentence() . '?',
            'answer'     => $this->faker->paragraph(),
            'is_active'  => true,
            'sort_order' => $this->faker->numberBetween(0, 100),
        ];
    }

    public function inactive(): static
    {
        return $this->state(['is_active' => false]);
    }
}
