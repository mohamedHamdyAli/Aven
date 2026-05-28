<?php

namespace Webkul\AiSupport\Http\Controllers\Webhooks;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Webkul\AiSupport\Services\ConversationManager;

class MessengerWebhookController extends Controller
{
    public function __construct(protected ConversationManager $manager) {}

    public function verify(): Response
    {
        $mode      = request('hub_mode');
        $token     = request('hub_verify_token');
        $challenge = request('hub_challenge');

        $configToken = core()->getConfigData('ai-support.channels.messenger_verify_token');

        if ($mode === 'subscribe' && $token === $configToken) {
            return response($challenge, 200);
        }

        return response('Forbidden', 403);
    }

    public function receive(): Response
    {
        $data = request()->json()->all();

        try {
            $entries = $data['entry'] ?? [];
            foreach ($entries as $entry) {
                foreach ($entry['messaging'] ?? [] as $event) {
                    $psid    = $event['sender']['id'] ?? null;
                    $message = $event['message'] ?? null;

                    if ($psid && isset($message['text'])) {
                        $this->manager->receive('messenger', $psid, $message['text']);
                    }
                }
            }
        } catch (\Throwable $e) {
            // Always return 200 to prevent Meta from retrying
        }

        return response('OK', 200);
    }
}
