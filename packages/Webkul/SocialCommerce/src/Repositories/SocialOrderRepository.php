<?php

namespace Webkul\SocialCommerce\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Webkul\SocialCommerce\Models\SocialOrder;

class SocialOrderRepository extends BaseRepository
{
    public function model(): string
    {
        return SocialOrder::class;
    }
}
