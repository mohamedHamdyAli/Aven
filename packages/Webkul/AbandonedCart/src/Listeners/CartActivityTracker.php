<?php

namespace Webkul\AbandonedCart\Listeners;

use Illuminate\Support\Str;
use Webkul\Checkout\Contracts\Cart as CartContract;
use Webkul\Checkout\Contracts\CartItem as CartItemContract;

class CartActivityTracker
{
    /**
     * Stamp last_activity_at and ensure notification_token exists whenever the cart changes.
     *
     * Some events (checkout.cart.update.after) pass the CartItem rather than the Cart itself,
     * so we resolve the Cart from whichever object we receive.
     */
    public function onCartActivity(CartContract|CartItemContract $cartOrItem): void
    {
        $cart = $cartOrItem instanceof CartItemContract
            ? $cartOrItem->cart
            : $cartOrItem;

        if (! $cart) {
            return;
        }

        // Debounce: skip if updated within the last 2 minutes to avoid a DB write on every page load
        if (
            $cart->last_activity_at
            && $cart->last_activity_at instanceof \Carbon\Carbon
            && $cart->last_activity_at->diffInMinutes(now()) < 2
            && ! empty($cart->notification_token)
        ) {
            return;
        }

        $updates = ['last_activity_at' => now()];

        if (empty($cart->notification_token)) {
            $updates['notification_token'] = Str::random(40);
        }

        $cart->update($updates);
    }

    /**
     * Mark the source cart as recovered when an order is successfully placed.
     */
    public function onOrderCreated(object $order): void
    {
        if ($order->cart_id) {
            \DB::table('cart')
                ->where('id', $order->cart_id)
                ->update(['recovery_status' => 'recovered']);
        }

        \DB::table('orders')
            ->where('id', $order->id)
            ->update(['cart_ip_address' => request()->ip()]);
    }
}
