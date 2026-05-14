<?php

namespace Webkul\SocialCommerce\Http\Controllers\Webhooks;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\SocialCommerce\Jobs\ProcessIncomingOrder;
use Webkul\SocialCommerce\Repositories\SocialChannelPlatformRepository;

class YoutubeWebhookController extends Controller
{
    public function __construct(
        protected SocialChannelPlatformRepository $platformRepository,
    ) {}

    public function handle(Request $request): JsonResponse
    {
        $payload   = $request->all();
        $eventType = $payload['kind'] ?? '';

        if (str_contains($eventType, 'order')) {
            $platform = $this->platformRepository->getActiveByPlatform('youtube')->first();

            if ($platform) {
                ProcessIncomingOrder::dispatch($platform->id, 'youtube', $payload);
            }
        }

        return response()->json(['status' => 'received']);
    }
}
