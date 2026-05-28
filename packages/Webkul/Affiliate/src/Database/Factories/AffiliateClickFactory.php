<?php

namespace Webkul\Affiliate\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Affiliate\Models\AffiliateClick;

class AffiliateClickFactory extends Factory
{
    protected $model = AffiliateClick::class;

    public function definition(): array
    {
        return [
            'affiliate_id' => AffiliateFactory::new()->create()->id,
            'ip'           => $this->faker->ipv4(),
            'url'          => $this->faker->url(),
        ];
    }
}
