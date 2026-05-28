<?php

namespace Webkul\PushNotification\Services;

use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;
use Webkul\PushNotification\Models\PushSubscription;

class PushService
{
    public function vapidKeys(): array
    {
        $public  = config('push-notification.vapid_public_key');
        $private = config('push-notification.vapid_private_key');

        return compact('public', 'private');
    }

    public function sendToAll(string $title, string $body, ?string $url = null, ?string $icon = null): int
    {
        $keys = $this->vapidKeys();

        if (! $keys['public'] || ! $keys['private']) {
            return 0;
        }

        $push = new WebPush([
            'VAPID' => [
                'subject'    => config('app.url'),
                'publicKey'  => $keys['public'],
                'privateKey' => $keys['private'],
            ],
        ]);

        $payload = json_encode(array_filter([
            'title' => $title,
            'body'  => $body,
            'url'   => $url,
            'icon'  => $icon,
        ]));

        $sent = 0;
        $dead = [];

        foreach (PushSubscription::all() as $sub) {
            try {
                $subscription = Subscription::create([
                    'endpoint' => $sub->endpoint,
                    'keys'     => ['p256dh' => $sub->p256dh, 'auth' => $sub->auth],
                ]);

                $push->queueNotification($subscription, $payload);
                $sent++;
            } catch (\Throwable) {
                $dead[] = $sub->id;
            }
        }

        foreach ($push->flush() as $report) {
            if ($report->isSubscriptionExpired()) {
                $dead[] = null;
                PushSubscription::where('endpoint', $report->getRequest()->getUri()->__toString())->delete();
            }
        }

        if ($dead) {
            PushSubscription::whereIn('id', array_filter($dead))->delete();
        }

        return $sent;
    }
}
