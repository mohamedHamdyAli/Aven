<?php

namespace Webkul\SocialCommerce\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Webkul\SocialCommerce\Repositories\SocialChannelPlatformRepository;
use Webkul\SocialCommerce\Repositories\SocialOrderRepository;

class ProcessIncomingOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        protected int $platformId,
        protected string $platformName,
        protected array $orderData,
    ) {}

    public function handle(
        SocialChannelPlatformRepository $platformRepository,
        SocialOrderRepository $orderRepository,
    ): void {
        $platform = $platformRepository->find($this->platformId);

        if (! $platform) {
            return;
        }

        $externalOrderId = $this->orderData['id']
            ?? $this->orderData['order_id']
            ?? uniqid($this->platformName.'_');

        try {
            $existing = $orderRepository->findWhere([
                'social_channel_platform_id' => $this->platformId,
                'external_order_id'          => $externalOrderId,
            ])->first();

            if ($existing) {
                return;
            }

            $socialOrder = $orderRepository->create([
                'social_channel_platform_id' => $this->platformId,
                'external_order_id'          => $externalOrderId,
                'platform_data'              => $this->orderData,
                'sync_status'                => 'pending',
            ]);

            event('social-commerce.order.received', [$platform, $socialOrder, $this->orderData]);
        } catch (\Throwable $e) {
            Log::warning("SocialCommerce: order processing failed [{$this->platformName}] {$externalOrderId}: {$e->getMessage()}");

            throw $e;
        }
    }
}
