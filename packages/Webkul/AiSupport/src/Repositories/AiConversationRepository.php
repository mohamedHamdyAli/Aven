<?php

namespace Webkul\AiSupport\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\AiSupport\Contracts\AiConversation;

class AiConversationRepository extends Repository
{
    public function model(): string
    {
        return AiConversation::class;
    }

    public function findOrCreateByChannel(string $channel, string $identifier, ?int $customerId = null): \Webkul\AiSupport\Models\AiConversation
    {
        $conversation = $this->model
            ->where('channel', $channel)
            ->where('channel_identifier', $identifier)
            ->whereIn('status', ['open', 'pending_review'])
            ->latest()
            ->first();

        if (! $conversation) {
            $conversation = $this->create([
                'channel'            => $channel,
                'channel_identifier' => $identifier,
                'customer_id'        => $customerId,
                'status'             => 'open',
            ]);
        }

        return $conversation;
    }
}
