<?php

namespace Webkul\BulkDeal\Listeners;

use Webkul\BulkDeal\Helpers\BulkDeal as BulkDealHelper;

class Cart
{
    public function __construct(protected BulkDealHelper $bulkDealHelper) {}

    public function applyBulkDeals(): void
    {
        $this->bulkDealHelper->applyToCart();
    }
}
