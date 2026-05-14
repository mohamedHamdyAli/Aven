<?php

namespace Webkul\AbandonedCart\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Webkul\AbandonedCart\DataGrids\AbandonedCartDataGrid;
use Webkul\AbandonedCart\Jobs\SendAbandonedCartNotification;
use Webkul\Checkout\Models\CartProxy;

class AbandonedCartController extends Controller
{
    public function index(): mixed
    {
        if (request()->ajax()) {
            return app(AbandonedCartDataGrid::class)->toJson();
        }

        return view('abandoned-cart::admin.index');
    }

    public function sendNow(int $cartId): RedirectResponse
    {
        $cart = CartProxy::findOrFail($cartId);

        $nextAttempt = $cart->notification_count + 1;
        $maxAttempts = (int) (core()->getConfigData('sales.abandoned_cart.general.max_notification_attempts') ?? 3);

        if ($nextAttempt > $maxAttempts) {
            return back()->with('warning', trans('abandoned-cart::app.admin.max-attempts-reached'));
        }

        SendAbandonedCartNotification::dispatch($cartId, $nextAttempt);

        return back()->with('success', trans('abandoned-cart::app.admin.notification-queued'));
    }

    public function markRecovered(int $cartId): JsonResponse
    {
        DB::table('cart')->where('id', $cartId)->update(['recovery_status' => 'recovered']);

        return response()->json(['message' => trans('abandoned-cart::app.admin.marked-recovered')]);
    }

    public function expire(int $cartId): JsonResponse
    {
        DB::table('cart')->where('id', $cartId)->update([
            'is_active'       => 0,
            'recovery_status' => 'expired',
        ]);

        return response()->json(['message' => trans('abandoned-cart::app.admin.cart-expired')]);
    }
}
