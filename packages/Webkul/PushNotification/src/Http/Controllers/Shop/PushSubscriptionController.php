<?php

namespace Webkul\PushNotification\Http\Controllers\Shop;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\PushNotification\Models\PushSubscription;

class PushSubscriptionController extends Controller
{
    public function subscribe(Request $request)
    {
        $data = $request->validate([
            'endpoint' => 'required|string|max:500',
            'p256dh'   => 'nullable|string',
            'auth'     => 'nullable|string',
        ]);

        $data['customer_id'] = auth()->guard('customer')->id();

        PushSubscription::updateOrCreate(
            ['endpoint' => $data['endpoint']],
            $data
        );

        return response()->json(['status' => 'subscribed']);
    }

    public function unsubscribe(Request $request)
    {
        PushSubscription::where('endpoint', $request->input('endpoint'))->delete();

        return response()->json(['status' => 'unsubscribed']);
    }

    public function vapidKey()
    {
        return response()->json(['key' => config('push-notification.vapid_public_key')]);
    }
}
