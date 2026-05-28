<?php

namespace Webkul\SocialCommerce\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\SocialCommerce\Models\SocialOrder;

class SocialOrderFactory extends Factory
{
    protected $model = SocialOrder::class;

    public function definition(): array
    {
        return [
            'social_channel_platform_id' => null,
            'order_id'                   => null,
            'external_order_id'          => $this->faker->unique()->numerify('EXT-######'),
            'platform_data'              => [],
            'sync_status'                => 'synced',
            'error_message'              => null,
        ];
    }

    public function failed(): static
    {
        return $this->state(['sync_status' => 'failed', 'error_message' => 'Sync failed']);
    }
}
