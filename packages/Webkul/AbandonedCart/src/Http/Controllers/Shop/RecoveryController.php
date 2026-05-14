<?php

namespace Webkul\AbandonedCart\Http\Controllers\Shop;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Webkul\AbandonedCart\Repositories\AbandonedCartNotificationRepository;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\CartProxy;

class RecoveryController extends Controller
{
    public function __construct(
        protected AbandonedCartNotificationRepository $notifRepo
    ) {}

    /**
     * Restore a cart from a signed recovery link and redirect to checkout.
     */
    public function recover(string $token): RedirectResponse
    {
        if (! request()->hasValidSignature()) {
            abort(403, trans('abandoned-cart::app.recovery.invalid-link'));
        }

        $cart = CartProxy::where('notification_token', $token)
            ->where('is_active', 1)
            ->first();

        if (! $cart) {
            return redirect()->route('shop.home.index')
                ->with('warning', trans('abandoned-cart::app.recovery.cart-expired'));
        }

        Cart::setCart($cart);

        $this->notifRepo->markClicked($cart->id);

        $cart->update(['recovery_status' => 'recovering']);

        return redirect()->route('shop.checkout.cart.index')
            ->with('success', trans('abandoned-cart::app.recovery.cart-restored'));
    }

    /**
     * One-click unsubscribe from cart recovery notifications.
     */
    public function unsubscribe(string $token): RedirectResponse
    {
        DB::table('cart')
            ->where('notification_token', $token)
            ->update(['notification_opt_in' => 0]);

        cookie()->queue(cookie()->forget('bagisto_cart_recovery'));

        return redirect()->route('shop.home.index')
            ->with('info', trans('abandoned-cart::app.recovery.unsubscribed'));
    }
}
