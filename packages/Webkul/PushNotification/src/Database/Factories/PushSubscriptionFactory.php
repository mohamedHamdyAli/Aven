<?php

namespace Webkul\PushNotification\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\PushNotification\Models\PushSubscription;

class PushSubscriptionFactory extends Factory
{
    protected $model = PushSubscription::class;

    public function definition(): array
    {
        return [
            'customer_id' => null,
            'endpoint'    => 'https://fcm.googleapis.com/fcm/send/' . $this->faker->unique()->sha256(),
            'p256dh'      => base64_encode($this->faker->sha256()),
            'auth'        => base64_encode($this->faker->sha1()),
        ];
    }
}
