<?php

namespace Webkul\SocialCommerce\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\SocialCommerce\Models\SocialProductSync;

class SocialProductSyncFactory extends Factory
{
    protected $model = SocialProductSync::class;

    public function definition(): array
    {
        return [
            'social_channel_platform_id' => null,
            'product_id'                 => null,
            'external_product_id'        => $this->faker->unique()->numerify('FB-######'),
            'sync_status'                => 'synced',
            'error_message'              => null,
            'synced_at'                  => now(),
        ];
    }

    public function failed(): static
    {
        return $this->state(['sync_status' => 'failed', 'error_message' => 'Catalog sync failed', 'synced_at' => null]);
    }
}
