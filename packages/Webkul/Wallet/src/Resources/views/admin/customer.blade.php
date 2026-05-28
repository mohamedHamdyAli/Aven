<x-admin::layouts>
    <x-slot:title>Wallet — {{ $customer->first_name }} {{ $customer->last_name }}</x-slot>

    <div class="flex items-center justify-between gap-4">
        <p class="text-xl font-bold text-gray-800">
            Wallet: {{ $customer->first_name }} {{ $customer->last_name }}
        </p>
        <a href="{{ route('admin.wallet.index') }}" class="secondary-button">← Back</a>
    </div>

    @if (session('success'))
        <div class="mt-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    {{-- Balance Card --}}
    <div class="mt-6 flex gap-4">
        <div class="rounded-xl border border-gray-200 bg-white px-6 py-5">
            <p class="text-xs text-gray-500">Current Balance</p>
            <p class="text-3xl font-bold text-green-600">{{ number_format($balance, 2) }}</p>
        </div>
    </div>

    {{-- Actions --}}
    <div class="mt-6 grid gap-4 sm:grid-cols-2">
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="mb-3 text-sm font-semibold text-gray-700">Issue Credit</p>
            <form method="POST" action="{{ route('admin.wallet.issue') }}" class="space-y-3">
                @csrf
                <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                <input type="number" name="amount" step="0.01" min="0.01" placeholder="Amount"
                       class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" required>
                <input type="text" name="note" placeholder="Note (optional)"
                       class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                <button class="primary-button w-full justify-center">Issue Credit</button>
            </form>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="mb-3 text-sm font-semibold text-gray-700">Revoke Credit</p>
            <form method="POST" action="{{ route('admin.wallet.revoke') }}" class="space-y-3">
                @csrf
                <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                <input type="number" name="amount" step="0.01" min="0.01" placeholder="Amount"
                       class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" required>
                <input type="text" name="note" placeholder="Note (optional)"
                       class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                <button class="bg-red-600 hover:bg-red-700 text-white rounded-lg px-4 py-2 text-sm font-semibold w-full">Revoke Credit</button>
            </form>
        </div>
    </div>

    {{-- Transaction History --}}
    <div class="mt-6 overflow-hidden rounded-xl border border-gray-200 bg-white">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50 text-xs text-gray-500">
                    <th class="px-4 py-3 text-left">Date</th>
                    <th class="px-4 py-3 text-left">Type</th>
                    <th class="px-4 py-3 text-left">Note</th>
                    <th class="px-4 py-3 text-right">Amount</th>
                    <th class="px-4 py-3 text-right">Balance After</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $tx)
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="px-4 py-3 text-xs text-gray-400">{{ $tx->created_at->format('d M Y H:i') }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-0.5 text-xs font-semibold
                                {{ $tx->type === 'credit' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($tx->type) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ $tx->note ?? '—' }}</td>
                        <td class="px-4 py-3 text-right font-semibold {{ $tx->type === 'credit' ? 'text-green-700' : 'text-red-700' }}">
                            {{ $tx->type === 'credit' ? '+' : '-' }}{{ number_format($tx->amount, 2) }}
                        </td>
                        <td class="px-4 py-3 text-right text-gray-600">{{ number_format($tx->balance_after, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-sm text-gray-400">No transactions.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $transactions->links() }}</div>
</x-admin::layouts>
