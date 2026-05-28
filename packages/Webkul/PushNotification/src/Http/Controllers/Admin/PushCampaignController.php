<?php

namespace Webkul\PushNotification\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\PushNotification\Models\PushCampaign;
use Webkul\PushNotification\Models\PushSubscription;
use Webkul\PushNotification\Services\PushService;

class PushCampaignController extends Controller
{
    public function __construct(protected PushService $push) {}

    public function index()
    {
        $campaigns         = PushCampaign::latest()->paginate(20);
        $subscriberCount   = PushSubscription::count();

        return view('push-notification::admin.index', compact('campaigns', 'subscriberCount'));
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:100',
            'body'  => 'required|string|max:255',
            'url'   => 'nullable|url',
            'icon'  => 'nullable|url',
        ]);

        $sent = $this->push->sendToAll($data['title'], $data['body'], $data['url'] ?? null, $data['icon'] ?? null);

        PushCampaign::create(array_merge($data, ['sent_count' => $sent]));

        session()->flash('success', "Push sent to {$sent} subscribers.");

        return back();
    }
}
