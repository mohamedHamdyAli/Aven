<x-admin::layouts>
    <x-slot:title>Push Notifications</x-slot>

    <div class="flex items-center justify-between gap-4">
        <div>
            <p class="text-xl font-bold text-gray-800">Push Notifications</p>
            <p class="text-sm text-gray-500">{{ number_format($subscriberCount) }} active subscribers</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mt-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    @php
        $pubKey = config('push-notification.vapid_public_key');
    @endphp
    @if (!$pubKey)
        <div class="mt-4 rounded-lg border border-yellow-200 bg-yellow-50 p-4 text-sm text-yellow-800">
            <strong>Setup required:</strong> Add <code>VAPID_PUBLIC_KEY</code> and <code>VAPID_PRIVATE_KEY</code> to your <code>.env</code> file.
            Generate them by running:<br>
            <code class="mt-1 block bg-yellow-100 px-2 py-1 rounded font-mono text-xs">php artisan push:generate-keys</code>
        </div>
    @endif

    {{-- Send Campaign --}}
    <div class="mt-6 rounded-xl border border-gray-200 bg-white p-5">
        <p class="mb-4 text-sm font-semibold text-gray-700">Send Push Notification</p>
        <form method="POST" action="{{ route('admin.push.send') }}" class="space-y-4">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-xs text-gray-500">Title</label>
                    <input type="text" name="title" maxlength="100"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" required>
                </div>
                <div>
                    <label class="mb-1 block text-xs text-gray-500">URL (on click)</label>
                    <input type="url" name="url"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                           placeholder="https://yourstore.com/sale">
                </div>
            </div>
            <div>
                <label class="mb-1 block text-xs text-gray-500">Message Body</label>
                <textarea name="body" rows="2" maxlength="255"
                          class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" required></textarea>
            </div>
            <button type="submit" class="primary-button" {{ !$pubKey ? 'disabled' : '' }}>
                Send to All Subscribers
            </button>
        </form>
    </div>

    {{-- Campaign History --}}
    <div class="mt-6 overflow-hidden rounded-xl border border-gray-200 bg-white">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50 text-xs text-gray-500">
                    <th class="px-4 py-3 text-left">Title</th>
                    <th class="px-4 py-3 text-left">Message</th>
                    <th class="px-4 py-3 text-center">Sent To</th>
                    <th class="px-4 py-3 text-right">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($campaigns as $c)
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $c->title }}</td>
                        <td class="px-4 py-3 text-gray-500 max-w-xs truncate">{{ $c->body }}</td>
                        <td class="px-4 py-3 text-center text-gray-600">{{ number_format($c->sent_count) }}</td>
                        <td class="px-4 py-3 text-right text-xs text-gray-400">{{ $c->created_at->format('d M Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-12 text-center text-sm text-gray-400">No campaigns sent yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $campaigns->links() }}</div>
</x-admin::layouts>
