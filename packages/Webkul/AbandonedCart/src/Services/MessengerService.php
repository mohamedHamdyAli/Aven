<?php

namespace Webkul\AbandonedCart\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MessengerService
{
    private const SEND_API = 'https://graph.facebook.com/v18.0/me/messages';

    /**
     * Send a text message to a Messenger user by their Page-Scoped ID (PSID).
     *
     * Requires the user to have previously messaged the Page (24-hour window rule).
     * For abandoned cart, the PSID must be stored from a prior "Send to Messenger"
     * plugin opt-in on the checkout page.
     */
    public function send(string $psid, string $text): bool
    {
        $token = core()->getConfigData('sales.abandoned_cart.channels.messenger_page_access_token');

        if (! $token || ! $psid) {
            return false;
        }

        try {
            $response = Http::timeout(15)
                ->post(self::SEND_API.'?access_token='.$token, [
                    'recipient' => ['id' => $psid],
                    'message'   => ['text' => $text],
                    'messaging_type' => 'MESSAGE_TAG',
                    'tag'            => 'POST_PURCHASE_UPDATE',
                ]);

            if ($response->failed()) {
                Log::warning('Messenger API error', [
                    'status'   => $response->status(),
                    'response' => $response->json(),
                ]);

                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('Messenger send exception: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Build an abandoned-cart reminder message for Messenger.
     */
    public function buildAbandonedCartMessage(object $cart, int $attempt): string
    {
        $storeName = core()->getConfigData('general.store_information.name') ?? config('app.name');
        $firstName = $cart->customer_first_name ?? 'there';
        $recoveryUrl = route('shop.abandoned-cart.recover', ['token' => $cart->notification_token]);

        $messages = [
            1 => "Hi {$firstName}, you left something in your {$storeName} cart! Complete your order here: {$recoveryUrl}",
            2 => "Don't forget! Your {$storeName} cart is waiting. Items may sell out. Complete your purchase: {$recoveryUrl}",
            3 => "Last reminder — your {$storeName} cart will expire soon. Checkout now: {$recoveryUrl}",
        ];

        return $messages[$attempt] ?? $messages[1];
    }
}
