<?php

namespace Webkul\AiSupport\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Webkul\AiSupport\DataGrids\ConversationDataGrid;
use Webkul\AiSupport\Repositories\AiConversationRepository;
use Webkul\AiSupport\Repositories\AiMessageRepository;
use Webkul\AiSupport\Services\ConversationManager;

class ConversationController extends Controller
{
    public function __construct(
        protected AiConversationRepository $conversationRepository,
        protected AiMessageRepository $messageRepository,
        protected ConversationManager $manager
    ) {}

    public function index(): mixed
    {
        if (request()->ajax()) {
            return app(ConversationDataGrid::class)->toJson();
        }

        return view('ai-support::admin.conversations.index');
    }

    public function show(int $id): mixed
    {
        $conversation = $this->conversationRepository->findOrFail($id);
        $conversation->load('messages', 'customer');

        return view('ai-support::admin.conversations.show', compact('conversation'));
    }

    public function updateMessage(int $conversationId, int $messageId): JsonResponse
    {
        $message = $this->messageRepository->findOrFail($messageId);

        request()->validate(['content' => 'required|string']);

        $this->messageRepository->update([
            'content' => request('content'),
            'status'  => 'edited',
        ], $messageId);

        return response()->json(['success' => true]);
    }

    public function send(int $id): JsonResponse|RedirectResponse
    {
        $conversation = $this->conversationRepository->findOrFail($id);

        $pendingMessage = $this->messageRepository->model
            ->where('conversation_id', $id)
            ->where('status', 'pending_review')
            ->latest()
            ->first();

        if (! $pendingMessage) {
            return response()->json(['error' => 'No pending message found.'], 404);
        }

        $text = $pendingMessage->ai_draft;
        if ($pendingMessage->status === 'edited') {
            $text = $pendingMessage->content;
        }

        $this->manager->deliver($conversation->channel, $conversation->channel_identifier, $text);

        $this->messageRepository->update([
            'content' => $text,
            'status'  => 'sent',
            'sent_at' => now(),
        ], $pendingMessage->id);

        session()->flash('success', 'Message sent successfully.');

        return redirect()->route('admin.ai-support.conversations.show', $id);
    }

    public function handoff(int $id): RedirectResponse
    {
        $this->conversationRepository->update(['status' => 'human_handoff'], $id);

        // Notify the customer
        $conversation = $this->conversationRepository->find($id);
        $this->manager->deliver(
            $conversation->channel,
            $conversation->channel_identifier,
            'You are now connected with a human support agent. They will respond shortly.'
        );

        session()->flash('success', 'Conversation handed off to human agent.');

        return redirect()->route('admin.ai-support.conversations.show', $id);
    }

    public function close(int $id): RedirectResponse
    {
        $this->conversationRepository->update(['status' => 'closed'], $id);

        session()->flash('success', 'Conversation closed.');

        return redirect()->route('admin.ai-support.conversations.index');
    }

    public function reply(int $id): RedirectResponse
    {
        $conversation = $this->conversationRepository->findOrFail($id);

        request()->validate(['content' => 'required|string|max:2000']);

        $this->messageRepository->create([
            'conversation_id' => $id,
            'role'            => 'admin',
            'content'         => request('content'),
            'status'          => 'sent',
            'sent_at'         => now(),
        ]);

        $this->manager->deliver($conversation->channel, $conversation->channel_identifier, request('content'));

        session()->flash('success', 'Reply sent.');

        return redirect()->route('admin.ai-support.conversations.show', $id);
    }
}
