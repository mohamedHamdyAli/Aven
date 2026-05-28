<?php

namespace Webkul\AiSupport\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\AiSupport\Models\AiConversation;

class AiConversationFactory extends Factory
{
    protected $model = AiConversation::class;

    public function definition(): array
    {
        return [
            'channel'            => $this->faker->randomElement(['web', 'whatsapp', 'messenger']),
            'channel_identifier' => $this->faker->uuid(),
            'customer_id'        => null,
            'status'             => 'open',
            'assigned_admin_id'  => null,
        ];
    }

    public function open(): static
    {
        return $this->state(['status' => 'open']);
    }

    public function handedOff(): static
    {
        return $this->state(['status' => 'human_handoff']);
    }

    public function closed(): static
    {
        return $this->state(['status' => 'closed']);
    }
}
