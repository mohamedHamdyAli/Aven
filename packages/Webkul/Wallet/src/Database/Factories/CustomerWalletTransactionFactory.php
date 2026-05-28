<?php

namespace Webkul\Wallet\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Wallet\Models\CustomerWalletTransaction;

class CustomerWalletTransactionFactory extends Factory
{
    protected $model = CustomerWalletTransaction::class;

    public function definition(): array
    {
        return [
            'customer_id'   => null,
            'order_id'      => null,
            'type'          => $this->faker->randomElement(['credit', 'debit', 'refund']),
            'amount'        => $this->faker->randomFloat(4, 5, 500),
            'balance_after' => $this->faker->randomFloat(4, 0, 1000),
            'note'          => $this->faker->sentence(),
        ];
    }
}
