<?php

namespace Webkul\BulkDeal\Repositories;

use Webkul\Core\Eloquent\Repository;

class BulkDealRepository extends Repository
{
    public function model(): string
    {
        return 'Webkul\BulkDeal\Contracts\BulkDeal';
    }

    public function getActiveDeals(): \Illuminate\Database\Eloquent\Collection
    {
        $now = now();

        return $this->where('status', 1)
            ->where(function ($q) use ($now) {
                $q->whereNull('starts_from')->orWhere('starts_from', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('ends_till')->orWhere('ends_till', '>=', $now);
            })
            ->orderBy('sort_order')
            ->get();
    }
}
