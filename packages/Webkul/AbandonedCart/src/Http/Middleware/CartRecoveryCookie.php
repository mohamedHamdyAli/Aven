<?php

namespace Webkul\AbandonedCart\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Webkul\Checkout\Facades\Cart;

class CartRecoveryCookie
{
    private const COOKIE_NAME = 'bagisto_cart_recovery';

    private const COOKIE_DAYS = 30;

    public function handle(Request $request, Closure $next): Response
    {
        // Attempt to restore a guest cart from the recovery cookie when the session has no cart
        if (! auth('customer')->check() && ! session()->has('cart')) {
            $token = $request->cookie(self::COOKIE_NAME);

            if ($token) {
                $cart = \DB::table('cart')
                    ->where('notification_token', $token)
                    ->where('is_active', 1)
                    ->where('recovery_status', '!=', 'expired')
                    ->first();

                if ($cart) {
                    $cartModel = \Webkul\Checkout\Models\CartProxy::find($cart->id);

                    if ($cartModel) {
                        Cart::setCart($cartModel);
                    }
                }
            }
        }

        $response = $next($request);

        // Set / refresh recovery cookie for the active guest cart
        $cart = Cart::getCart();

        if (
            $cart
            && $cart->is_guest
            && $cart->notification_token
            && $cart->notification_opt_in
        ) {
            $response->headers->setCookie(
                Cookie::make(
                    self::COOKIE_NAME,
                    $cart->notification_token,
                    self::COOKIE_DAYS * 24 * 60,
                    '/',
                    null,
                    $request->isSecure(),
                    true,     // httpOnly
                    false,
                    'Strict'  // sameSite
                )
            );
        }

        return $response;
    }
}
