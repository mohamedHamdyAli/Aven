<?php

namespace Webkul\StoreLocator\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\StoreLocator\Models\StoreLocator;

class StoreLocatorFactory extends Factory
{
    protected $model = StoreLocator::class;

    public function definition(): array
    {
        return [
            'name'          => $this->faker->company() . ' Store',
            'address'       => $this->faker->address(),
            'latitude'      => (float) $this->faker->latitude(22, 31),
            'longitude'     => (float) $this->faker->longitude(25, 37),
            'phone'         => $this->faker->phoneNumber(),
            'working_hours' => ['Mon-Fri' => '9am-9pm', 'Sat-Sun' => '10am-6pm'],
            'image'         => null,
            'status'        => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(['status' => false]);
    }
}
