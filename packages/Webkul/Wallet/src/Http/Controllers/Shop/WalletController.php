<?php

namespace Webkul\Wallet\Http\Controllers\Shop;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\Wallet\Services\WalletService;

class WalletController extends Controller
{
    public function __construct(protected WalletService $wallet) {}

    public function index()
    {
        $customer     = auth()->guard('customer')->user();
        $balance      = $this->wallet->balance($customer->id);
        $transactions = $this->wallet->transactions($customer->id);

        return view('wallet::shop.index', compact('balance', 'transactions'));
    }

    public function apply(Request $request)
    {
        $customer = auth()->guard('customer')->user();
        $cart     = app(\Webkul\Checkout\Facades\Cart::class)->getCart();

        if (! $cart) {
            return response()->json(['error' => 'No active cart'], 422);
        }

        $amount  = (float) $request->input('amount', PHP_INT_MAX);
        $applied = $this->wallet->applyToCart($cart->id, $customer->id, $amount);

        return response()->json([
            'applied'  => $applied,
            'balance'  => $this->wallet->balance($customer->id),
            'message'  => number_format($applied, 2) . ' store credit applied.',
        ]);
    }

    public function remove()
    {
        $cart = app(\Webkul\Checkout\Facades\Cart::class)->getCart();

        if ($cart) {
            $this->wallet->removeFromCart($cart->id);
        }

        return response()->json(['message' => 'Store credit removed.']);
    }
}
