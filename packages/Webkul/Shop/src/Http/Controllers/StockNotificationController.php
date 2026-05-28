<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Http\Request;
use Webkul\Product\Models\StockNotification;

class StockNotificationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'email'      => 'nullable|email',
            'phone'      => 'nullable|string|max:20',
        ]);

        if (! $request->email && ! $request->phone) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide an email or phone number.',
            ], 422);
        }

        $exists = StockNotification::where('product_id', $request->product_id)
            ->where(function ($q) use ($request) {
                if ($request->email) {
                    $q->where('email', $request->email);
                }
                if ($request->phone) {
                    $q->orWhere('phone', $request->phone);
                }
            })
            ->where('notified', false)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => true,
                'message' => "You're already on the list! We'll notify you when it's back.",
            ]);
        }

        StockNotification::create([
            'product_id' => $request->product_id,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'channel_id' => core()->getCurrentChannel()->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Done! We'll notify you as soon as it's back in stock.",
        ]);
    }
}
