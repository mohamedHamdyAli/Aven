<?php

namespace Webkul\GiftCard\Http\Controllers\Shop;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\Checkout\Facades\Cart;
use Webkul\GiftCard\Services\GiftCardService;

class GiftCardController extends Controller
{
    public function __construct(private GiftCardService $service) {}

    public function apply(Request $request): JsonResponse
    {
        $request->validate(['code' => 'required|string|max:50']);

        $cart = Cart::getCart();

        if (! $cart) {
            return new JsonResponse(['success' => false, 'message' => 'Cart not found.'], 422);
        }

        $result = $this->service->applyToCart($cart->id, $request->input('code'));

        return new JsonResponse($result, $result['success'] ? 200 : 422);
    }

    public function remove(): JsonResponse
    {
        $cart = Cart::getCart();

        if ($cart) {
            $this->service->removeFromCart($cart->id);
        }

        return new JsonResponse(['success' => true, 'message' => 'Gift card removed.']);
    }
}
