<?php

namespace Webkul\AiSupport\Http\Controllers\Shop;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Webkul\AiSupport\Services\ConversationManager;

class ChatController extends Controller
{
    public function __construct(protected ConversationManager $manager) {}

    public function send(): JsonResponse
    {
        $data = request()->validate([
            'message'    => 'required|string|max:2000',
            'session_id' => 'required|string|max:100',
        ]);

        $customerId = auth('customer')->id();

        $result = $this->manager->receive(
            channel: 'web_chat',
            identifier: $data['session_id'],
            message: $data['message'],
            customerId: $customerId
        );

        return response()->json($result);
    }

    public function history(): JsonResponse
    {
        $sessionId = request()->input('session_id');

        if (! $sessionId) {
            return response()->json(['messages' => []]);
        }

        $conversation = app(\Webkul\AiSupport\Repositories\AiConversationRepository::class)
            ->findWhere(['channel' => 'web_chat', 'channel_identifier' => $sessionId])
            ->first();

        if (! $conversation) {
            return response()->json(['messages' => []]);
        }

        $messages = $conversation->messages()
            ->whereIn('status', ['sent', 'edited'])
            ->orderBy('created_at')
            ->get(['role', 'content', 'created_at']);

        return response()->json(['messages' => $messages]);
    }
}
