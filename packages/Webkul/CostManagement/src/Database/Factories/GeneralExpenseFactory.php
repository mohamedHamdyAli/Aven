<?php

namespace Webkul\CostManagement\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\CostManagement\Models\GeneralExpense;

class GeneralExpenseFactory extends Factory
{
    protected $model = GeneralExpense::class;

    public function definition(): array
    {
        return [
            'title'        => $this->faker->words(3, true),
            'category'     => $this->faker->randomElement(['rent', 'salaries', 'marketing', 'utilities', 'shipping', 'software', 'other']),
            'amount'       => $this->faker->randomFloat(2, 100, 10000),
            'expense_date' => $this->faker->date('Y-m-d'),
            'is_recurring' => false,
            'frequency'    => null,
            'notes'        => $this->faker->sentence(),
        ];
    }

    public function recurring(): static
    {
        return $this->state(['is_recurring' => true, 'frequency' => 'monthly']);
    }
}
