<?php

namespace Webkul\SocialCommerce\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\SocialCommerce\Models\SocialChannelPlatform;

class SocialChannelPlatformFactory extends Factory
{
    protected $model = SocialChannelPlatform::class;

    public function definition(): array
    {
        return [
            'channel_id'       => null,
            'platform'         => $this->faker->randomElement(['facebook', 'instagram', 'whatsapp']),
            'is_active'        => true,
            'page_url'         => $this->faker->url(),
            'page_id'          => $this->faker->numerify('##########'),
            'pixel_id'         => null,
            'app_id'           => null,
            'app_secret'       => null,
            'access_token'     => null,
            'catalog_id'       => null,
            'phone_number_id'  => null,
            'last_synced_at'   => null,
        ];
    }

    public function inactive(): static
    {
        return $this->state(['is_active' => false]);
    }
}
