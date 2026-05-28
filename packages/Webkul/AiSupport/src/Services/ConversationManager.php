<?php

namespace Webkul\AiSupport\Services;

use Illuminate\Support\Facades\Log;
use Webkul\AiSupport\Channels\EmailChannel;
use Webkul\AiSupport\Channels\MessengerChannel;
use Webkul\AiSupport\Channels\WebChatChannel;
use Webkul\AiSupport\Channels\WhatsAppChannel;
use Webkul\AiSupport\Models\AiConversation;
use Webkul\AiSupport\Repositories\AiConversationRepository;
use Webkul\AiSupport\Repositories\AiMessageRepository;

class ConversationManager
{
    public function __construct(
        protected AiConversationRepository $conversationRepository,
        protected AiMessageRepository $messageRepository,
        protected ClaudeService $claude,
        protected WebChatChannel $webChat,
        protected WhatsAppChannel $whatsApp,
        protected MessengerChannel $messenger,
        protected EmailChannel $email
    ) {}

    public function receive(string $channel, string $identifier, string $message, ?int $customerId = null): array
    {
        $conversation = $this->conversationRepository->findOrCreateByChannel($channel, $identifier, $customerId);

        // Block AI responses once handed off to human
        if ($conversation->isHandedOff()) {
            $this->messageRepository->create([
                'conversation_id' => $conversation->id,
                'role'            => 'customer',
                'content'         => $message,
                'status'          => 'sent',
                'sent_at'         => now(),
            ]);

            return [
                'status'  => 'human_handoff',
                'message' => 'You are connected with our support team. They will reply shortly.',
            ];
        }

        // Save customer message
        $this->messageRepository->create([
            'conversation_id' => $conversation->id,
            'role'            => 'customer',
            'content'         => $message,
            'status'          => 'sent',
            'sent_at'         => now(),
        ]);

        // Build history for Claude
        $history = $this->messageRepository->getHistory($conversation->id);

        // Append the just-saved customer message if not already there
        if (empty($history) || end($history)['role'] !== 'user') {
            $history[] = ['role' => 'user', 'content' => $message];
        }

        try {
            $aiResponse = $this->claude->chat($history);
        } catch (\Throwable $e) {
            Log::error('AiSupport ClaudeService error', ['error' => $e->getMessage()]);
            $aiResponse = 'Sorry, I encountered an issue. Please try again.';
        }

        // Check if AI requested a handoff
        $requestedHandoff = $aiResponse === '__HANDOFF__' || str_contains($aiResponse, '__HANDOFF__');
        if ($requestedHandoff) {
            $aiResponse = 'I\'m connecting you with one of our support agents. They will be with you shortly.';
            $this->conversationRepository->update(['status' => 'human_handoff'], $conversation->id);
        }

        $autoReply = (bool) (core()->getConfigData('ai-support.general.auto_reply') ?? true);

        $msgStatus = $autoReply ? 'sent' : 'pending_review';

        $aiMessage = $this->messageRepository->create([
            'conversation_id' => $conversation->id,
            'role'            => 'ai',
            'content'         => $autoReply ? $aiResponse : '',
            'ai_draft'        => $aiResponse,
            'status'          => $msgStatus,
            'sent_at'         => $autoReply ? now() : null,
        ]);

        if ($autoReply && ! $requestedHandoff) {
            $this->deliver($channel, $identifier, $aiResponse);
        }

        return [
            'status'          => $requestedHandoff ? 'human_handoff' : ($autoReply ? 'replied' : 'pending_review'),
            'message'         => $autoReply ? $aiResponse : 'Your message has been received.',
            'conversation_id' => $conversation->id,
            'message_id'      => $aiMessage->id,
        ];
    }

    public function deliver(string $channel, string $identifier, string $text): void
    {
        match ($channel) {
            'whatsapp'  => $this->whatsApp->send($identifier, $text),
            'messenger' => $this->messenger->send($identifier, $text),
            'email'     => $this->email->send($identifier, $text),
            default     => null, // web_chat: response returned in HTTP response
        };
    }
}
