<?php

namespace Webkul\AiSupport\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\AiSupport\Models\AiMessage;

class AiMessageFactory extends Factory
{
    protected $model = AiMessage::class;

    public function definition(): array
    {
        return [
            'conversation_id' => null,
            'role'            => $this->faker->randomElement(['user', 'assistant', 'system']),
            'content'         => $this->faker->paragraph(),
            'ai_draft'        => null,
            'status'          => 'sent',
            'sent_at'         => now(),
        ];
    }
}
