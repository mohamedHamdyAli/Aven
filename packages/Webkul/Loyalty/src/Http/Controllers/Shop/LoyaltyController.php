<?php

namespace Webkul\Loyalty\Http\Controllers\Shop;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\Checkout\Facades\Cart;
use Webkul\Loyalty\Services\LoyaltyService;

class LoyaltyController extends Controller
{
    public function __construct(private LoyaltyService $loyalty) {}

    public function apply(Request $request)
    {
        $customer = auth()->guard('customer')->user();
        if (! $customer) {
            return response()->json(['success' => false, 'message' => 'Login required'], 401);
        }

        $points = (float) $request->points;
        $cart = Cart::getCart();
        if (! $cart) {
            return response()->json(['success' => false, 'message' => 'Cart not found'], 400);
        }

        $discount = $this->loyalty->applyToCart($cart->id, $customer->id, $points);
        if ($discount <= 0) {
            return response()->json(['success' => false, 'message' => 'Unable to apply points']);
        }

        return response()->json([
            'success'            => true,
            'discount'           => $discount,
            'formatted_discount' => core()->currency($discount),
        ]);
    }

    public function remove(Request $request)
    {
        $cart = Cart::getCart();
        if ($cart) {
            $this->loyalty->removeFromCart($cart->id);
        }

        return response()->json(['success' => true]);
    }
}
