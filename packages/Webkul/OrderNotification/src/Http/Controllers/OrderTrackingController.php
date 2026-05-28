<?php

namespace Webkul\OrderNotification\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class OrderTrackingController extends Controller
{
    public function index()
    {
        return view('order_notification::track.index');
    }

    public function track(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string|max:50',
            'email'    => 'required|email',
        ]);

        $order = DB::table('orders')
            ->where('increment_id', $request->order_id)
            ->where('customer_email', $request->email)
            ->first();

        if (! $order) {
            return back()->withErrors(['order_id' => 'No order found with that ID and email. Please check your details.']);
        }

        $items = DB::table('order_items')
            ->where('order_id', $order->id)
            ->get();

        $shipments = DB::table('shipments')
            ->where('order_id', $order->id)
            ->get();

        return view('order_notification::track.result', compact('order', 'items', 'shipments'));
    }
}
