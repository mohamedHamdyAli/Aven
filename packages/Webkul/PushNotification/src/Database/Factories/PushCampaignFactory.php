<?php

namespace Webkul\PushNotification\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\PushNotification\Models\PushCampaign;

class PushCampaignFactory extends Factory
{
    protected $model = PushCampaign::class;

    public function definition(): array
    {
        return [
            'title'      => $this->faker->sentence(4),
            'body'       => $this->faker->sentence(),
            'icon'       => null,
            'url'        => $this->faker->url(),
            'sent_count' => $this->faker->numberBetween(0, 10000),
        ];
    }
}
