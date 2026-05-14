<?php

namespace Webkul\AbandonedCart\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Webkul\AbandonedCart\Mail\AbandonedCartRecovery;
use Webkul\AbandonedCart\Repositories\AbandonedCartNotificationRepository;
use Webkul\AbandonedCart\Services\MessengerService;
use Webkul\AbandonedCart\Services\WhatsAppService;
use Webkul\Checkout\Models\CartProxy;

class SendAbandonedCartNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        protected int $cartId,
        protected int $attemptNumber
    ) {}

    public function handle(
        AbandonedCartNotificationRepository $notifRepo,
        WhatsAppService $whatsApp,
        MessengerService $messenger
    ): void {
        $cart = CartProxy::find($this->cartId);

        if (! $cart || ! $cart->is_active || $cart->recovery_status === 'recovered') {
            return;
        }

        if ($this->shouldSendEmail($cart)) {
            try {
                Mail::queue(new AbandonedCartRecovery($cart, $this->attemptNumber));
                $notifRepo->recordSent($this->cartId, 'email', $this->attemptNumber);
            } catch (\Throwable $e) {
                $notifRepo->recordFailed($this->cartId, 'email', $this->attemptNumber, $e->getMessage());
                Log::warning('Abandoned cart email failed', ['cart_id' => $this->cartId, 'error' => $e->getMessage()]);
            }
        }

        if ($this->shouldSendWhatsApp($cart)) {
            $phone = $cart->customer_phone
                ?? optional($cart->billingAddress)->phone;

            if ($phone) {
                $components = $whatsApp->buildAbandonedCartComponents($cart);
                $sent = $whatsApp->sendTemplate($phone, 'abandoned_cart_reminder', $components);
                $sent
                    ? $notifRepo->recordSent($this->cartId, 'whatsapp', $this->attemptNumber)
                    : $notifRepo->recordFailed($this->cartId, 'whatsapp', $this->attemptNumber, 'API call failed');
            }
        }

        if ($this->shouldSendMessenger($cart)) {
            $psid = $this->getMessengerPsid($cart);

            if ($psid) {
                $text = $messenger->buildAbandonedCartMessage($cart, $this->attemptNumber);
                $sent = $messenger->send($psid, $text);
                $sent
                    ? $notifRepo->recordSent($this->cartId, 'messenger', $this->attemptNumber)
                    : $notifRepo->recordFailed($this->cartId, 'messenger', $this->attemptNumber, 'API call failed');
            }
        }

        $cart->increment('notification_count');
        $cart->update(['recovery_status' => 'recovering']);
    }

    private function shouldSendEmail(object $cart): bool
    {
        return (bool) core()->getConfigData('sales.abandoned_cart.channels.email_enabled')
            && ! empty($cart->customer_email);
    }

    private function shouldSendWhatsApp(object $cart): bool
    {
        return (bool) core()->getConfigData('sales.abandoned_cart.channels.whatsapp_enabled')
            && (bool) core()->getConfigData('sales.abandoned_cart.channels.whatsapp_api_token');
    }

    private function shouldSendMessenger(object $cart): bool
    {
        return (bool) core()->getConfigData('sales.abandoned_cart.channels.messenger_enabled')
            && (bool) core()->getConfigData('sales.abandoned_cart.channels.messenger_page_access_token');
    }

    private function getMessengerPsid(object $cart): ?string
    {
        if (! $cart->customer_id) {
            return null;
        }

        $customer = \DB::table('customers')->where('id', $cart->customer_id)->first();

        if (! $customer) {
            return null;
        }

        $additional = is_string($customer->additional ?? null)
            ? json_decode($customer->additional, true)
            : (array) ($customer->additional ?? []);

        return $additional['messenger_psid'] ?? null;
    }
}
