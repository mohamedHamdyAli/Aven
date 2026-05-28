<x-admin::layouts>
    <x-slot:title>Store Credit / Wallets</x-slot>

    <div class="flex items-center justify-between gap-4">
        <p class="text-xl font-bold text-gray-800">Store Credit / Wallets</p>
    </div>

    @if (session('success'))
        <div class="mt-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    {{-- Issue Credit Form --}}
    <div class="mt-6 rounded-xl border border-gray-200 bg-white p-5">
        <p class="mb-4 text-sm font-semibold text-gray-700">Issue Store Credit</p>
        <form method="POST" action="{{ route('admin.wallet.issue') }}" class="flex flex-wrap items-end gap-3">
            @csrf
            <div>
                <label class="mb-1 block text-xs text-gray-500">Customer ID</label>
                <input type="number" name="customer_id" class="w-36 rounded-lg border border-gray-300 px-3 py-2 text-sm" required>
            </div>
            <div>
                <label class="mb-1 block text-xs text-gray-500">Amount</label>
                <input type="number" name="amount" step="0.01" min="0.01" class="w-32 rounded-lg border border-gray-300 px-3 py-2 text-sm" required>
            </div>
            <div>
                <label class="mb-1 block text-xs text-gray-500">Note</label>
                <input type="text" name="note" class="w-64 rounded-lg border border-gray-300 px-3 py-2 text-sm" placeholder="e.g. Refund, Loyalty bonus">
            </div>
            <button type="submit" class="primary-button">Issue Credit</button>
        </form>
    </div>

    {{-- Wallets Table --}}
    <div class="mt-6 overflow-hidden rounded-xl border border-gray-200 bg-white">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50 text-xs text-gray-500">
                    <th class="px-4 py-3 text-left font-semibold">Customer</th>
                    <th class="px-4 py-3 text-left font-semibold">Email</th>
                    <th class="px-4 py-3 text-right font-semibold">Balance</th>
                    <th class="px-4 py-3 text-right font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($wallets as $wallet)
                    @php $c = \Webkul\Customer\Models\Customer::find($wallet->customer_id) @endphp
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $c?->first_name }} {{ $c?->last_name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $c?->email }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-green-700">{{ number_format($wallet->balance, 2) }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.wallet.customer', $wallet->customer_id) }}"
                               class="text-xs text-indigo-600 hover:underline">History</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-16 text-center text-sm text-gray-400">No wallet balances yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $wallets->links() }}</div>
</x-admin::layouts>
