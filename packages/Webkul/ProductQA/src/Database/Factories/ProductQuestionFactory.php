<?php

namespace Webkul\ProductQA\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\ProductQA\Models\ProductQuestion;

class ProductQuestionFactory extends Factory
{
    protected $model = ProductQuestion::class;

    public function definition(): array
    {
        return [
            'product_id'     => null,
            'customer_id'    => null,
            'customer_name'  => $this->faker->name(),
            'customer_email' => $this->faker->safeEmail(),
            'question'       => $this->faker->sentence() . '?',
            'answer'         => $this->faker->paragraph(),
            'status'         => 'approved',
            'is_published'   => true,
        ];
    }

    public function pending(): static
    {
        return $this->state(['status' => 'pending', 'is_published' => false]);
    }
}
