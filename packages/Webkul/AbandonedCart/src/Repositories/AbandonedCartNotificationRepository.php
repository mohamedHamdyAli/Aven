<?php

namespace Webkul\AbandonedCart\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Webkul\AbandonedCart\Contracts\AbandonedCartNotification;

class AbandonedCartNotificationRepository extends BaseRepository
{
    public function model(): string
    {
        return AbandonedCartNotification::class;
    }

    public function getLastAttemptForCart(int $cartId): ?object
    {
        return app($this->model())::query()
            ->where('cart_id', $cartId)
            ->whereIn('status', ['sent', 'queued'])
            ->latest('created_at')
            ->first();
    }

    public function recordSent(int $cartId, string $channel, int $attempt): self
    {
        app($this->model())::create([
            'cart_id'        => $cartId,
            'channel'        => $channel,
            'attempt_number' => $attempt,
            'status'         => 'sent',
            'sent_at'        => now(),
            'created_at'     => now(),
        ]);

        return $this;
    }

    public function recordFailed(int $cartId, string $channel, int $attempt, string $error): self
    {
        app($this->model())::create([
            'cart_id'        => $cartId,
            'channel'        => $channel,
            'attempt_number' => $attempt,
            'status'         => 'failed',
            'error_message'  => $error,
            'created_at'     => now(),
        ]);

        return $this;
    }

    public function markOpened(int $cartId, string $channel): void
    {
        app($this->model())::query()
            ->where('cart_id', $cartId)
            ->where('channel', $channel)
            ->where('status', 'sent')
            ->latest('created_at')
            ->first()
            ?->update(['status' => 'opened', 'opened_at' => now()]);
    }

    public function markClicked(int $cartId): void
    {
        app($this->model())::query()
            ->where('cart_id', $cartId)
            ->whereIn('status', ['sent', 'opened'])
            ->latest('created_at')
            ->first()
            ?->update(['status' => 'clicked', 'clicked_at' => now()]);
    }
}
