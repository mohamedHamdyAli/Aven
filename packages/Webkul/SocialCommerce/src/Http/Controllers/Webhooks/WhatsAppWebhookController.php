<?php

namespace Webkul\SocialCommerce\Http\Controllers\Webhooks;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\SocialCommerce\Jobs\ProcessIncomingOrder;
use Webkul\SocialCommerce\Repositories\SocialChannelPlatformRepository;

class WhatsAppWebhookController extends Controller
{
    public function __construct(
        protected SocialChannelPlatformRepository $platformRepository,
    ) {}

    public function verify(Request $request): mixed
    {
        $mode      = $request->query('hub_mode');
        $token     = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        if ($mode === 'subscribe' && $token === env('WHATSAPP_WEBHOOK_VERIFY_TOKEN')) {
            return response($challenge, 200)->header('Content-Type', 'text/plain');
        }

        return response()->json(['error' => 'Forbidden'], 403);
    }

    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();

        foreach ($payload['entry'] ?? [] as $entry) {
            foreach ($entry['changes'] ?? [] as $change) {
                $messages = $change['value']['messages'] ?? [];

                foreach ($messages as $message) {
                    if (($message['type'] ?? '') === 'order') {
                        $platform = $this->platformRepository->getActiveByPlatform('whatsapp')->first();

                        if ($platform) {
                            ProcessIncomingOrder::dispatch($platform->id, 'whatsapp', $message['order'] ?? $message);
                        }
                    }
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
