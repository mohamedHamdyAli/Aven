<?php

namespace Webkul\SocialCommerce\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\SocialCommerce\Repositories\SocialChannelPlatformRepository;
use Webkul\SocialCommerce\Repositories\SocialProductSyncRepository;
use Webkul\SocialCommerce\Services\CatalogSyncManager;

class SyncProductsToPlatform implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        protected int $platformId,
    ) {}

    public function handle(
        SocialChannelPlatformRepository $platformRepository,
        ProductRepository $productRepository,
        SocialProductSyncRepository $syncRepository,
        CatalogSyncManager $syncManager,
    ): void {
        $platform = $platformRepository->find($this->platformId);

        if (! $platform || ! $platform->is_active) {
            return;
        }

        $products = $productRepository->findWhere(['status' => 1]);

        foreach ($products as $product) {
            try {
                $externalId = $syncManager->syncProduct($platform, $product);

                $syncRepository->updateOrCreateSync($platform->id, $product->id, [
                    'external_product_id' => $externalId,
                    'sync_status'         => 'synced',
                    'error_message'       => null,
                    'synced_at'           => now(),
                ]);
            } catch (\Throwable $e) {
                Log::warning("SocialCommerce: sync failed for product {$product->id} on {$platform->platform}: {$e->getMessage()}");

                $syncRepository->updateOrCreateSync($platform->id, $product->id, [
                    'sync_status'   => 'failed',
                    'error_message' => $e->getMessage(),
                ]);
            }
        }

        $platformRepository->update(['last_synced_at' => now()], $platform->id);
    }
}
