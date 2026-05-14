<?php

namespace Webkul\EgyptShipping\Http\Controllers\Shop;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\Sales\Repositories\OrderRepository;

class OrderTrackingController extends Controller
{
    public function __construct(protected OrderRepository $orderRepository) {}

    public function index()
    {
        return view('egypt-shipping::shop.track-order.index');
    }

    public function show(Request $request)
    {
        $request->validate([
            'increment_id' => 'required|string',
            'email'        => 'required|email',
        ]);

        $order = $this->orderRepository
            ->with(['items.product', 'shipments.items', 'shipping_address', 'billing_address', 'payment'])
            ->findWhere([
                'increment_id'   => $request->increment_id,
                'customer_email' => $request->email,
            ])
            ->first();

        if (! $order) {
            return redirect()->route('egypt-shipping.track-order.index')
                ->with('error', __('egypt-shipping::app.track-order.not-found'));
        }

        return view('egypt-shipping::shop.track-order.result', compact('order'));
    }
}
