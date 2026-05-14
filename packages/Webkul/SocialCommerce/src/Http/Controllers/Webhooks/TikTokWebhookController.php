<?php

namespace Webkul\SocialCommerce\Http\Controllers\Webhooks;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\SocialCommerce\Jobs\ProcessIncomingOrder;
use Webkul\SocialCommerce\Repositories\SocialChannelPlatformRepository;

class TikTokWebhookController extends Controller
{
    public function __construct(
        protected SocialChannelPlatformRepository $platformRepository,
    ) {}

    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();

        if (($payload['type'] ?? '') === 'ORDER_STATUS_CHANGE') {
            $platform = $this->platformRepository->getActiveByPlatform('tiktok')->first();

            if ($platform) {
                ProcessIncomingOrder::dispatch($platform->id, 'tiktok', $payload['data'] ?? []);
            }
        }

        return response()->json(['code' => 0, 'message' => 'success']);
    }
}
