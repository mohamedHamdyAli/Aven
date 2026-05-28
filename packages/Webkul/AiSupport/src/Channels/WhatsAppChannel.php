<?php

namespace Webkul\AiSupport\Channels;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppChannel
{
    private const API_BASE = 'https://graph.facebook.com/v18.0';

    public function send(string $phoneNumber, string $text): bool
    {
        $token   = core()->getConfigData('ai-support.channels.whatsapp_token');
        $phoneId = core()->getConfigData('ai-support.channels.whatsapp_phone_id');

        if (! $token || ! $phoneId) {
            Log::warning('AiSupport: WhatsApp not configured');

            return false;
        }

        try {
            $response = Http::withToken($token)
                ->timeout(15)
                ->post(self::API_BASE."/{$phoneId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to'                => $phoneNumber,
                    'type'              => 'text',
                    'text'              => ['body' => $text],
                ]);

            if ($response->failed()) {
                Log::warning('AiSupport WhatsApp send failed', ['status' => $response->status(), 'body' => $response->body()]);

                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('AiSupport WhatsApp exception', ['error' => $e->getMessage()]);

            return false;
        }
    }
}
