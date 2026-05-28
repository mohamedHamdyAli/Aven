<x-admin::layouts>
    <x-slot:title>New Profit Distribution</x-slot>

    <div class="flex flex-col gap-6 max-w-3xl">

        {{-- Header --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.cost_management.distributions.index') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                ← Back
            </a>
            <p class="text-xl font-bold text-gray-800 dark:text-white">New Profit Distribution</p>
        </div>

        {{-- Errors --}}
        @if($errors->any())
            <div class="rounded-lg bg-red-50 p-4 text-sm text-red-700 border border-red-200">{{ $errors->first() }}</div>
        @endif

        @if($shareholders->isEmpty())
            <div class="rounded-lg bg-yellow-50 p-5 text-yellow-800 border border-yellow-200">
                No active shareholders found.
                <a href="{{ route('admin.cost_management.shareholders.index') }}" class="underline font-medium">Add shareholders first →</a>
            </div>
        @else

        <form method="POST" action="{{ route('admin.cost_management.distributions.store') }}" class="flex flex-col gap-5" id="distForm">
            @csrf

            {{-- Period --}}
            <div class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-900">
                <p class="mb-4 font-semibold text-gray-800 dark:text-white">Distribution Period</p>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label-field">From *</label>
                        <input type="date" name="period_from" value="{{ old('period_from', $from) }}" required class="input-field w-full">
                    </div>
                    <div>
                        <label class="label-field">To *</label>
                        <input type="date" name="period_to" value="{{ old('period_to', $to) }}" required class="input-field w-full">
                    </div>
                </div>
            </div>

            {{-- Net Profit --}}
            <div class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-900">
                <p class="mb-4 font-semibold text-gray-800 dark:text-white">Net Profit</p>
                <div class="relative max-w-sm">
                    <input
                        type="number"
                        name="net_profit"
                        id="netProfitInput"
                        value="{{ old('net_profit') }}"
                        step="0.01"
                        min="0"
                        required
                        placeholder="0.00"
                        class="input-field w-full pr-16"
                    >
                    <span class="absolute inset-y-0 right-3 flex items-center text-gray-400 text-sm">{{ core()->getCurrentCurrency()->symbol }}</span>
                </div>
                <p class="mt-2 text-xs text-gray-400">
                    Total shareholder allocation: <strong>{{ number_format($totalPct, 2) }}%</strong>
                    @if($totalPct < 100)
                        <span class="text-yellow-600">({{ number_format(100 - $totalPct, 2) }}% unallocated)</span>
                    @endif
                </p>
            </div>

            {{-- Breakdown Preview --}}
            <div class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-900">
                <p class="mb-4 font-semibold text-gray-800 dark:text-white">Breakdown Preview</p>
                <table class="w-full text-sm">
                    <thead class="text-xs text-gray-500 uppercase">
                        <tr>
                            <th class="pb-2 text-left">Shareholder</th>
                            <th class="pb-2 text-right">Share %</th>
                            <th class="pb-2 text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700" id="breakdownBody">
                        @foreach($shareholders as $sh)
                            <tr>
                                <td class="py-2 text-gray-700 dark:text-gray-300">{{ $sh->name }}</td>
                                <td class="py-2 text-right text-gray-500">{{ number_format($sh->percentage, 2) }}%</td>
                                <td class="py-2 text-right font-semibold text-green-600" data-pct="{{ $sh->percentage }}">—</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Notes --}}
            <div class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-900">
                <label class="label-field">Notes (optional)</label>
                <textarea name="notes" rows="3" class="input-field w-full" placeholder="e.g. Q1 2026 profit distribution">{{ old('notes') }}</textarea>
            </div>

            <button type="submit" class="primary-button w-full max-w-sm">Create Distribution & Save</button>
        </form>

        @endif
    </div>

    <style>
        .input-field { @apply rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white; }
        .label-field { @apply mb-1 block text-xs font-medium text-gray-600 dark:text-gray-400; }
    </style>

    <script>
        const input = document.getElementById('netProfitInput');
        const cells = document.querySelectorAll('#breakdownBody td[data-pct]');

        function updateBreakdown() {
            const net = parseFloat(input.value) || 0;
            cells.forEach(cell => {
                const pct = parseFloat(cell.dataset.pct);
                const amount = (pct / 100) * net;
                cell.textContent = amount > 0
                    ? new Intl.NumberFormat(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(amount)
                    : '—';
            });
        }

        input.addEventListener('input', updateBreakdown);
    </script>
</x-admin::layouts>
