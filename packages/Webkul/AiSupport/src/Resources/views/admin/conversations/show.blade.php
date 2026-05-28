<x-admin::layouts>
    <x-slot:title>Conversation #{{ $conversation->id }}</x-slot:title>

    <div class="flex items-center justify-between mb-5 flex-wrap gap-3">
        <div>
            <a href="{{ route('admin.ai-support.conversations.index') }}" class="text-blue-600 text-sm">← Back</a>
            <p class="text-xl font-bold text-gray-800 mt-1">
                Conversation #{{ $conversation->id }}
                <span class="ml-2 text-sm font-normal px-2 py-1 rounded
                    @if($conversation->status === 'open') bg-green-100 text-green-700
                    @elseif($conversation->status === 'pending_review') bg-yellow-100 text-yellow-700
                    @elseif($conversation->status === 'human_handoff') bg-blue-100 text-blue-700
                    @else bg-gray-100 text-gray-500 @endif">
                    {{ ucfirst(str_replace('_', ' ', $conversation->status)) }}
                </span>
            </p>
            <p class="text-sm text-gray-500 mt-1">
                Channel: <strong>{{ ucfirst(str_replace('_', ' ', $conversation->channel)) }}</strong>
                &nbsp;·&nbsp; Identifier: <code>{{ $conversation->channel_identifier }}</code>
                @if($conversation->customer)
                    &nbsp;·&nbsp; Customer: {{ $conversation->customer->first_name }} {{ $conversation->customer->last_name }}
                @endif
            </p>
        </div>

        <div class="flex gap-2 flex-wrap">
            @if($conversation->status !== 'human_handoff' && $conversation->status !== 'closed')
                <form method="POST" action="{{ route('admin.ai-support.conversations.handoff', $conversation->id) }}">
                    @csrf
                    <button class="px-4 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">
                        Transfer to Human Agent
                    </button>
                </form>
            @endif

            @if($conversation->status !== 'closed')
                <form method="POST" action="{{ route('admin.ai-support.conversations.close', $conversation->id) }}">
                    @csrf
                    <button class="px-4 py-2 bg-gray-500 text-white rounded text-sm hover:bg-gray-600">
                        Close Conversation
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
    @endif

    {{-- Message thread --}}
    <div class="bg-white rounded-lg shadow p-4 mb-6 max-h-[500px] overflow-y-auto flex flex-col gap-3" id="message-thread">
        @forelse($conversation->messages as $message)
            <div class="flex @if($message->role === 'customer') justify-start @else justify-end @endif">
                <div class="max-w-[70%] rounded-lg px-4 py-2 text-sm
                    @if($message->role === 'customer') bg-gray-100 text-gray-800
                    @elseif($message->role === 'ai') bg-indigo-50 text-indigo-900 border border-indigo-200
                    @else bg-green-50 text-green-900 border border-green-200 @endif">

                    <div class="flex items-center gap-2 mb-1">
                        <span class="font-semibold text-xs uppercase">
                            @if($message->role === 'customer') Customer
                            @elseif($message->role === 'ai') 🤖 AI
                            @else 👤 Agent
                            @endif
                        </span>
                        <span class="text-xs text-gray-400">{{ $message->created_at->format('H:i') }}</span>
                        @if($message->status === 'pending_review')
                            <span class="text-xs bg-yellow-200 text-yellow-800 px-1 rounded">Pending Review</span>
                        @elseif($message->status === 'edited')
                            <span class="text-xs bg-blue-200 text-blue-800 px-1 rounded">Edited</span>
                        @endif
                    </div>

                    @if($message->status === 'pending_review')
                        {{-- Editable draft --}}
                        <form method="POST" action="{{ route('admin.ai-support.conversations.send', $conversation->id) }}" class="mt-2">
                            @csrf
                            <textarea
                                name="draft_content"
                                class="w-full border rounded p-2 text-sm"
                                rows="3"
                                id="draft-{{ $message->id }}">{{ $message->ai_draft }}</textarea>
                            <div class="flex gap-2 mt-2">
                                <button type="button"
                                    onclick="saveEdit({{ $message->id }}, {{ $conversation->id }})"
                                    class="px-3 py-1 bg-indigo-600 text-white rounded text-xs hover:bg-indigo-700">
                                    Save & Send
                                </button>
                                <button type="button"
                                    onclick="rejectMessage({{ $message->id }}, {{ $conversation->id }})"
                                    class="px-3 py-1 bg-red-500 text-white rounded text-xs hover:bg-red-600">
                                    Reject
                                </button>
                            </div>
                        </form>
                    @else
                        <p>{{ $message->content }}</p>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-gray-400 text-center py-8">No messages yet.</p>
        @endforelse
    </div>

    {{-- Admin reply form (always visible when not closed) --}}
    @if($conversation->status !== 'closed')
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm font-semibold text-gray-700 mb-2">Send Manual Reply</p>
            <form method="POST" action="{{ route('admin.ai-support.conversations.reply', $conversation->id) }}">
                @csrf
                <textarea name="content" rows="3"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    placeholder="Type your reply..."></textarea>
                <button type="submit" class="mt-2 px-4 py-2 bg-indigo-600 text-white rounded text-sm hover:bg-indigo-700">
                    Send Reply
                </button>
            </form>
        </div>
    @endif

    <script>
    async function saveEdit(messageId, conversationId) {
        const text = document.getElementById('draft-' + messageId).value;
        await fetch(`/{{ config('app.admin_url', 'admin') }}/ai-support/conversations/${conversationId}/messages/${messageId}`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ content: text }),
        });
        // Then send
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('admin.ai-support.conversations.send', $conversation->id) }}`;
        form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}">`;
        document.body.appendChild(form);
        form.submit();
    }
    </script>
</x-admin::layouts>
