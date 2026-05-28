<?php

namespace Webkul\FlashSale\Console\Commands;

use Illuminate\Console\Command;
use Webkul\FlashSale\Services\FlashSaleService;

class SyncFlashSales extends Command
{
    protected $signature = 'flash:sync';

    protected $description = 'Activate pending and expire finished flash sales';

    public function handle(FlashSaleService $service): int
    {
        $activated = $service->syncActivatable();
        $expired   = $service->syncExpired();

        $this->info("Activated: {$activated}, Expired: {$expired}");

        return 0;
    }
}
