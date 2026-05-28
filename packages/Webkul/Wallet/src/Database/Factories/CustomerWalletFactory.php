<?php

namespace Webkul\Wallet\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Wallet\Models\CustomerWallet;

class CustomerWalletFactory extends Factory
{
    protected $model = CustomerWallet::class;

    public function definition(): array
    {
        return [
            'customer_id' => null,
            'balance'     => $this->faker->randomFloat(4, 0, 1000),
        ];
    }
}
