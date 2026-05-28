<?php

namespace Webkul\ShopTheLook\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\ShopTheLook\Models\ProductLookItem;

class ProductLookItemFactory extends Factory
{
    protected $model = ProductLookItem::class;

    public function definition(): array
    {
        return [
            'product_id'      => null,
            'look_product_id' => null,
            'sort_order'      => $this->faker->numberBetween(0, 50),
        ];
    }
}
