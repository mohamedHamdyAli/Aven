<?php

namespace Webkul\SizeGuide\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\SizeGuide\Models\SizeChartRow;

class SizeChartRowFactory extends Factory
{
    protected $model = SizeChartRow::class;

    public function definition(): array
    {
        $chest = (float) $this->faker->numberBetween(80, 120);
        $waist = (float) $this->faker->numberBetween(60, 100);

        return [
            'size_chart_id'    => null,
            'label'            => $this->faker->randomElement(['XS', 'S', 'M', 'L', 'XL', 'XXL']),
            'sort_order'       => $this->faker->numberBetween(0, 10),
            'eu_size'          => (string) $this->faker->numberBetween(34, 52),
            'uk_size'          => (string) $this->faker->numberBetween(6, 20),
            'us_size'          => (string) $this->faker->numberBetween(0, 18),
            'chest_min'        => $chest,
            'chest_max'        => $chest + 4,
            'waist_min'        => $waist,
            'waist_max'        => $waist + 4,
            'hips_min'         => null,
            'hips_max'         => null,
            'height_min'       => null,
            'height_max'       => null,
            'product_chest'    => null,
            'product_waist'    => null,
            'product_length'   => null,
            'product_shoulder' => null,
        ];
    }
}
