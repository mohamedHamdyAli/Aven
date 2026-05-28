<x-admin::layouts>
    <x-slot:title>Affiliate: {{ $affiliate->name }}</x-slot>

    <div class="flex items-center justify-between gap-4">
        <p class="text-xl font-bold text-gray-800">{{ $affiliate->name }}</p>
        <a href="{{ route('admin.affiliates.index') }}" class="secondary-button">← Back</a>
    </div>

    @if (session('success'))
        <div class="mt-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    {{-- Stats --}}
    <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-4">
        <div class="rounded-xl border border-gray-200 bg-white p-4">
            <p class="text-xs text-gray-500">Code</p>
            <p class="mt-1 font-mono text-lg font-bold text-indigo-700">{{ $affiliate->code }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4">
            <p class="text-xs text-gray-500">Commission Rate</p>
            <p class="mt-1 text-2xl font-bold text-gray-800">{{ $affiliate->commission_rate }}%</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4">
            <p class="text-xs text-gray-500">Total Earned</p>
            <p class="mt-1 text-2xl font-bold text-green-600">{{ number_format($affiliate->total_earned, 2) }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4">
            <p class="text-xs text-gray-500">Total Paid</p>
            <p class="mt-1 text-2xl font-bold text-gray-600">{{ number_format($affiliate->total_paid, 2) }}</p>
        </div>
    </div>

    {{-- Tracking Link --}}
    <div class="mt-4 rounded-xl border border-gray-100 bg-indigo-50 p-4 text-sm">
        <p class="mb-1 text-xs text-gray-500">Affiliate Link</p>
        <code class="text-indigo-700">{{ url('/ref/' . $affiliate->code) }}</code>
    </div>

    {{-- Mark Paid --}}
    <div class="mt-6 rounded-xl border border-gray-200 bg-white p-5">
        <p class="mb-3 text-sm font-semibold text-gray-700">Record Payment</p>
        <form method="POST" action="{{ route('admin.affiliates.mark-paid') }}" class="flex items-end gap-3">
            @csrf
            <input type="hidden" name="affiliate_id" value="{{ $affiliate->id }}">
            <div>
                <label class="mb-1 block text-xs text-gray-500">Amount</label>
                <input type="number" name="amount" step="0.01" min="0.01" required
                       class="w-36 rounded-lg border border-gray-300 px-3 py-2 text-sm">
            </div>
            <button class="primary-button">Mark Paid & Approve All</button>
        </form>
    </div>

    {{-- Commissions --}}
    <div class="mt-6 overflow-hidden rounded-xl border border-gray-200 bg-white">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50 text-xs text-gray-500">
                    <th class="px-4 py-3 text-left">Order</th>
                    <th class="px-4 py-3 text-right">Order Total</th>
                    <th class="px-4 py-3 text-right">Commission</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-right">Date</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($commissions as $c)
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-600">#{{ $c->order_id }}</td>
                        <td class="px-4 py-3 text-right">{{ number_format($c->order_total, 2) }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-green-700">{{ number_format($c->commission, 2) }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="rounded-full px-2 py-0.5 text-xs font-semibold
                                {{ $c->status === 'paid' ? 'bg-green-100 text-green-700' :
                                   ($c->status === 'approved' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700') }}">
                                {{ ucfirst($c->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right text-xs text-gray-400">{{ $c->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-right">
                            @if ($c->status === 'pending')
                                <button onclick="fetch('{{ route('admin.affiliates.commission.approve', $c->id) }}',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}}).then(()=>location.reload())"
                                        class="text-xs text-green-600 hover:underline">Approve</button>
                            @else
                                <span class="text-xs text-gray-300">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-10 text-center text-sm text-gray-400">No commissions.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $commissions->links() }}</div>
</x-admin::layouts>
