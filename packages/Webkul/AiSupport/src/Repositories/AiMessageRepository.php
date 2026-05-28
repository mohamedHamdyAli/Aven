<?php

namespace Webkul\AiSupport\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\AiSupport\Contracts\AiMessage;

class AiMessageRepository extends Repository
{
    public function model(): string
    {
        return AiMessage::class;
    }

    public function getHistory(int $conversationId): array
    {
        return $this->model
            ->where('conversation_id', $conversationId)
            ->whereIn('status', ['sent', 'edited'])
            ->orderBy('created_at')
            ->get()
            ->map(fn ($msg) => [
                'role'    => $msg->role === 'customer' ? 'user' : 'assistant',
                'content' => $msg->role === 'ai' && $msg->status === 'edited'
                    ? $msg->content // admin-edited version
                    : $msg->content,
            ])
            ->filter(fn ($msg) => in_array($msg['role'], ['user', 'assistant']))
            ->values()
            ->toArray();
    }
}
