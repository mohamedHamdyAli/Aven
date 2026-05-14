<?php

namespace Webkul\SocialCommerce\Http\Controllers\Webhooks;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\SocialCommerce\Jobs\ProcessIncomingOrder;
use Webkul\SocialCommerce\Repositories\SocialChannelPlatformRepository;

class FacebookWebhookController extends Controller
{
    public function __construct(
        protected SocialChannelPlatformRepository $platformRepository,
    ) {}

    public function verify(Request $request): mixed
    {
        $mode      = $request->query('hub_mode');
        $token     = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        if ($mode === 'subscribe' && $token === env('FACEBOOK_WEBHOOK_VERIFY_TOKEN')) {
            return response($challenge, 200)->header('Content-Type', 'text/plain');
        }

        return response()->json(['error' => 'Forbidden'], 403);
    }

    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();

        foreach ($payload['entry'] ?? [] as $entry) {
            foreach ($entry['changes'] ?? [] as $change) {
                if (($change['field'] ?? '') === 'orders') {
                    $platform = $this->platformRepository->getActiveByPlatform('facebook')->first();

                    if ($platform) {
                        ProcessIncomingOrder::dispatch($platform->id, 'facebook', $change['value'] ?? []);
                    }
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
