<?php

namespace Webkul\SizeGuide\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\SizeGuide\Models\SizeChart;

class SizeChartFactory extends Factory
{
    protected $model = SizeChart::class;

    public function definition(): array
    {
        return [
            'name'            => $this->faker->words(2, true) . ' Size Chart',
            'gender'          => $this->faker->randomElement(['men', 'women', 'kids', 'unisex']),
            'type'            => $this->faker->randomElement(['clothing', 'shoes', 'accessories']),
            'image'           => null,
            'image_overlays'  => [],
            'column_headers'  => ['EU', 'UK', 'US', 'Chest', 'Waist'],
        ];
    }
}
