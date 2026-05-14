<?php

namespace Webkul\Admin\Repositories;

use Webkul\Admin\Models\ChannelAdSpend;
use Webkul\Core\Eloquent\Repository;

class ChannelAdSpendRepository extends Repository
{
    public function model(): string
    {
        return ChannelAdSpend::class;
    }

    public function getSpendForPeriod(int $channelId, $startDate, $endDate): float
    {
        return (float) $this->resetModel()
            ->where('channel_id', $channelId)
            ->where('start_date', '>=', $startDate)
            ->where('end_date', '<=', $endDate)
            ->sum('amount');
    }
}
