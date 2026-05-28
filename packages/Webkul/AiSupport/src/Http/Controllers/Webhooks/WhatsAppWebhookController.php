<?php

namespace Webkul\AiSupport\Http\Controllers\Webhooks;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Webkul\AiSupport\Services\ConversationManager;

class WhatsAppWebhookController extends Controller
{
    public function __construct(protected ConversationManager $manager) {}

    public function verify(): Response
    {
        $mode      = request('hub_mode');
        $token     = request('hub_verify_token');
        $challenge = request('hub_challenge');

        $configToken = core()->getConfigData('ai-support.channels.whatsapp_verify_token');

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
                foreach ($entry['changes'] ?? [] as $change) {
                    $messages = $change['value']['messages'] ?? [];
                    foreach ($messages as $message) {
                        if (($message['type'] ?? '') === 'text') {
                            $phone = $message['from'];
                            $text  = $message['text']['body'] ?? '';

                            if ($text) {
                                $this->manager->receive('whatsapp', $phone, $text);
                            }
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            // Always return 200 to prevent Meta from retrying
        }

        return response('OK', 200);
    }
}
