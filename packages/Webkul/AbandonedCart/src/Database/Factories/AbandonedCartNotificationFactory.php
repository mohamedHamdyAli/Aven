<?php

namespace Webkul\AbandonedCart\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\AbandonedCart\Models\AbandonedCartNotification;

class AbandonedCartNotificationFactory extends Factory
{
    protected $model = AbandonedCartNotification::class;

    public function definition(): array
    {
        return [
            'cart_id'        => 1,
            'channel'        => $this->faker->randomElement(['whatsapp', 'messenger', 'email']),
            'attempt_number' => $this->faker->numberBetween(1, 3),
            'status'         => $this->faker->randomElement(['pending', 'sent', 'failed']),
            'sent_at'        => null,
            'opened_at'      => null,
            'clicked_at'     => null,
            'error_message'  => null,
            'created_at'     => now(),
        ];
    }
}
