<?php

namespace Webkul\GiftCard\Listeners;

use Illuminate\Support\Facades\DB;
use Webkul\GiftCard\Services\GiftCardService;

class GiftCardOrderListener
{
    public function __construct(private GiftCardService $service) {}

    public function onOrderSaved($order): void
    {
        $cart = DB::table('cart')->where('id', $order->cart_id ?? null)->first();

        if (! $cart || ! $cart->gift_card_code || $cart->gift_card_discount <= 0) {
            return;
        }

        DB::table('orders')->where('id', $order->id)->update([
            'gift_card_code'     => $cart->gift_card_code,
            'gift_card_discount' => $cart->gift_card_discount,
        ]);

        $this->service->redeemForOrder($cart->gift_card_code, (float) $cart->gift_card_discount);
    }
}
