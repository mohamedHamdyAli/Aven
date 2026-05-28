<?php

namespace Webkul\AiSupport\Channels;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MessengerChannel
{
    private const API_BASE = 'https://graph.facebook.com/v18.0';

    public function send(string $psid, string $text): bool
    {
        $token = core()->getConfigData('ai-support.channels.messenger_token');

        if (! $token) {
            Log::warning('AiSupport: Messenger not configured');

            return false;
        }

        try {
            $response = Http::timeout(15)
                ->post(self::API_BASE.'/me/messages?access_token='.$token, [
                    'recipient'      => ['id' => $psid],
                    'message'        => ['text' => $text],
                    'messaging_type' => 'RESPONSE',
                ]);

            if ($response->failed()) {
                Log::warning('AiSupport Messenger send failed', ['status' => $response->status(), 'body' => $response->body()]);

                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('AiSupport Messenger exception', ['error' => $e->getMessage()]);

            return false;
        }
    }
}
