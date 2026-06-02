<x-admin::layouts>
    <x-slot:title>Shareholders</x-slot>

    <div class="flex flex-col gap-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xl font-bold text-gray-800 dark:text-white">Shareholders</p>
                @php $totalContributed = $shareholders->sum(fn($s) => $s->totalContributed()); @endphp
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Total shares: <span class="font-semibold text-gray-700 dark:text-gray-200">{{ number_format($totalShares) }}</span>
                    @if($sharePrice > 0)
                        &nbsp;·&nbsp; Share value capital:
                        <span class="font-semibold text-blue-600">{{ core()->currency($totalCapital) }}</span>
                    @endif
                    @if($totalContributed > 0)
                        &nbsp;·&nbsp; Cash contributed:
                        <span class="font-semibold text-green-600">{{ core()->currency($totalContributed) }}</span>
                    @endif
                </p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.cost_management.balance_sheet.index') }}"
                   class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200">
                    Balance Sheet
                </a>
                <a href="{{ route('admin.cost_management.distributions.index') }}" class="primary-button">
                    Profit Distributions
                </a>
            </div>
        </div>

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="rounded-lg bg-green-50 p-4 text-sm text-green-700 border border-green-200">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="rounded-lg bg-red-50 p-4 text-sm text-red-700 border border-red-200">{{ $errors->first() }}</div>
        @endif

        {{-- Share Price Setting --}}
        <div class="rounded-lg border border-blue-200 bg-blue-50 p-4 dark:border-blue-800 dark:bg-blue-950">
            <form method="POST" action="{{ route('admin.cost_management.shareholders.settings') }}"
                  class="flex flex-wrap items-end gap-3">
                @csrf
                <div>
                    <label class="label-field">Share Price (EGP) — قيمة السهم الواحد</label>
                    <input type="number" name="share_price" step="0.01" min="0"
                           value="{{ $sharePrice > 0 ? $sharePrice : '' }}"
                           placeholder="e.g. 1000.00"
                           class="input-field w-48">
                </div>
                <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                    Update
                </button>
                @if($sharePrice > 0)
                    <p class="text-xs text-blue-600 self-end pb-2">
                        Current: <strong>{{ core()->currency($sharePrice) }}</strong> / share
                    </p>
                @endif
            </form>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- Shareholders List --}}
            <div class="lg:col-span-2 flex flex-col gap-3">
                @forelse($shareholders as $sh)
                    @php
                        $pct         = $totalShares > 0 ? round(($sh->shares / $totalShares) * 100, 2) : 0;
                        $investValue = $sh->shares * $sharePrice;
                    @endphp
                    <div class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-900">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 text-blue-700 text-lg font-bold dark:bg-blue-900 dark:text-blue-300">
                                    {{ strtoupper(substr($sh->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800 dark:text-white">{{ $sh->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $sh->email }}</p>
                                    @if($sh->phone)
                                        <p class="text-sm text-gray-500">{{ $sh->phone }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="text-right shrink-0">
                                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                    {{ number_format($sh->shares) }}
                                    <span class="text-sm font-normal text-gray-400">shares</span>
                                </p>
                                <p class="text-sm font-semibold text-gray-600 dark:text-gray-300">{{ number_format($pct, 2) }}%</p>
                                @if($sharePrice > 0)
                                    <p class="text-xs text-gray-400">{{ core()->currency($investValue) }}</p>
                                @endif
                                <p class="text-xs text-gray-400 mt-0.5">{{ $sh->active ? 'Active' : 'Inactive' }}</p>
                                <p class="text-sm font-semibold text-green-600 mt-1">
                                    Earned: {{ core()->currency($sh->totalEarned()) }}
                                </p>
                            </div>
                        </div>

                        {{-- Ownership bar --}}
                        <div class="mt-3 h-2 w-full rounded-full bg-gray-100 dark:bg-gray-800">
                            <div class="h-2 rounded-full bg-blue-500" style="width: {{ min($pct, 100) }}%"></div>
                        </div>

                        {{-- Capital Contributions — always visible --}}
                        <div class="mt-4 rounded-lg border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-950 p-3">
                            <div class="mb-3 flex items-center justify-between">
                                <p class="text-xs font-semibold text-green-700 dark:text-green-300">Capital Contributions</p>
                                <span class="text-xs font-bold text-green-700 dark:text-green-300">
                                    Total: {{ core()->currency($sh->totalContributed()) }}
                                </span>
                            </div>

                            {{-- Existing contributions list --}}
                            <div class="space-y-1.5">
                                @forelse($sh->contributions->sortByDesc('contributed_at') as $c)
                                    <div class="flex items-center justify-between rounded-md bg-white dark:bg-gray-800 px-3 py-2 text-xs">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="font-semibold text-gray-800 dark:text-gray-100">{{ core()->currency($c->amount) }}</span>
                                            <span class="text-gray-400">{{ $c->contributed_at->format('d M Y') }}</span>
                                            <span class="rounded bg-green-100 dark:bg-green-900 px-1.5 py-0.5 text-green-700 dark:text-green-300">
                                                {{ \Webkul\CostManagement\Models\CapitalContribution::types()[$c->type] ?? $c->type }}
                                            </span>
                                            @if($c->notes)
                                                <span class="text-gray-400 italic">{{ $c->notes }}</span>
                                            @endif
                                        </div>
                                        <form method="POST"
                                              action="{{ route('admin.cost_management.contributions.destroy', $c->id) }}"
                                              onsubmit="return confirm('Remove this contribution?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="ml-2 text-red-400 hover:text-red-600">✕</button>
                                        </form>
                                    </div>
                                @empty
                                    <p class="text-xs text-gray-400 py-1">No contributions recorded yet.</p>
                                @endforelse
                            </div>

                            {{-- Add contribution form --}}
                            <form method="POST" action="{{ route('admin.cost_management.contributions.store') }}"
                                  class="mt-3 grid grid-cols-2 gap-2 border-t border-green-200 dark:border-green-800 pt-3">
                                @csrf
                                <input type="hidden" name="shareholder_id" value="{{ $sh->id }}">
                                <div class="col-span-2">
                                    <label class="label-field">Amount (EGP) *</label>
                                    <input type="number" name="amount" step="0.01" min="0.01" required
                                           class="input-field w-full" placeholder="e.g. 24000.00">
                                </div>
                                <div>
                                    <label class="label-field">Date *</label>
                                    <input type="date" name="contributed_at" required
                                           value="{{ now()->toDateString() }}" class="input-field w-full">
                                </div>
                                <div>
                                    <label class="label-field">Type</label>
                                    <select name="type" class="input-field w-full">
                                        @foreach(\Webkul\CostManagement\Models\CapitalContribution::types() as $val => $label)
                                            <option value="{{ $val }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-span-2">
                                    <label class="label-field">Notes</label>
                                    <input type="text" name="notes" class="input-field w-full" placeholder="Optional...">
                                </div>
                                <div class="col-span-2">
                                    <button type="submit"
                                            class="w-full rounded-lg bg-green-600 px-4 py-2 text-xs font-semibold text-white hover:bg-green-700">
                                        + Add Contribution
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- Edit form --}}
                        <details class="mt-3">
                            <summary class="cursor-pointer text-xs text-blue-600 hover:underline">Edit</summary>
                            <form method="POST" action="{{ route('admin.cost_management.shareholders.update', $sh->id) }}" class="mt-3 grid grid-cols-2 gap-3">
                                @csrf @method('PUT')
                                <input type="text"   name="name"      value="{{ $sh->name }}"                    placeholder="Name"    required class="input-field col-span-2">
                                <input type="email"  name="email"     value="{{ $sh->email }}"                   placeholder="Email"           class="input-field">
                                <input type="text"   name="phone"     value="{{ $sh->phone }}"                   placeholder="Phone"           class="input-field">
                                <div class="col-span-2">
                                    <label class="label-field">Number of Shares *</label>
                                    <input type="number" name="shares" value="{{ $sh->shares }}" min="1" step="1" required class="input-field w-full">
                                    @if($sharePrice > 0)
                                        <p class="mt-1 text-xs text-gray-400">
                                            Investment value: <span class="font-semibold">{{ core()->currency($investValue) }}</span>
                                            &nbsp;·&nbsp; Ownership: <span class="font-semibold">{{ number_format($pct, 2) }}%</span>
                                        </p>
                                    @endif
                                </div>
                                <input type="date"   name="joined_at" value="{{ $sh->joined_at?->toDateString() }}" class="input-field">
                                <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                    <input type="checkbox" name="active" value="1" {{ $sh->active ? 'checked' : '' }}>
                                    Active
                                </label>
                                <textarea name="notes" placeholder="Notes" class="input-field col-span-2 h-16">{{ $sh->notes }}</textarea>
                                <div class="col-span-2">
                                    <button type="submit" class="primary-button text-sm">Save</button>
                                </div>
                            </form>

                            {{-- Delete form is OUTSIDE the edit form to prevent nested-form _method collision --}}
                            <form method="POST" action="{{ route('admin.cost_management.shareholders.destroy', $sh->id) }}"
                                  class="mt-2" onsubmit="return confirm('Delete this shareholder?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="rounded-lg border border-red-300 bg-red-50 px-3 py-1.5 text-sm text-red-600 hover:bg-red-100">
                                    Delete
                                </button>
                            </form>
                        </details>
                    </div>
                @empty
                    <div class="rounded-lg border border-dashed border-gray-300 p-10 text-center text-gray-400">
                        No shareholders yet. Add one using the form →
                    </div>
                @endforelse
            </div>

            {{-- Add Shareholder Form --}}
            <div class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-900 h-fit">
                <p class="mb-4 font-semibold text-gray-800 dark:text-white">Add Shareholder</p>
                <form method="POST" action="{{ route('admin.cost_management.shareholders.store') }}" class="flex flex-col gap-3" id="add-sh-form">
                    @csrf
                    <div>
                        <label class="label-field">Name *</label>
                        <input type="text" name="name" required class="input-field w-full" placeholder="e.g. Ahmed Hassan">
                    </div>
                    <div>
                        <label class="label-field">Email</label>
                        <input type="email" name="email" class="input-field w-full" placeholder="ahmed@example.com">
                    </div>
                    <div>
                        <label class="label-field">Phone</label>
                        <input type="text" name="phone" class="input-field w-full" placeholder="01xxxxxxxxx">
                    </div>
                    <div>
                        <label class="label-field">Number of Shares *</label>
                        <input type="number" name="shares" id="new-shares" min="1" step="1" required
                               class="input-field w-full" placeholder="e.g. 25"
                               oninput="calcNewSharePreview()">
                        <p id="new-share-preview" class="mt-1 text-xs text-gray-400 hidden"></p>
                    </div>
                    <div>
                        <label class="label-field">Joined Date</label>
                        <input type="date" name="joined_at" class="input-field w-full" value="{{ now()->toDateString() }}">
                    </div>
                    <div>
                        <label class="label-field">Notes</label>
                        <textarea name="notes" class="input-field w-full h-16" placeholder="Optional notes..."></textarea>
                    </div>
                    <button type="submit" class="primary-button w-full">Add Shareholder</button>
                </form>
            </div>
        </div>
    </div>

    <style>
        .input-field { @apply rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white; }
        .label-field { @apply mb-1 block text-xs font-medium text-gray-600 dark:text-gray-400; }
    </style>

    <script>
    (function () {
        var totalShares = {{ $totalShares }};
        var sharePrice  = {{ $sharePrice }};

        window.calcNewSharePreview = function () {
            var el      = document.getElementById('new-shares');
            var preview = document.getElementById('new-share-preview');
            var n       = parseInt(el.value) || 0;
            if (n <= 0 || !preview) return preview && preview.classList.add('hidden');

            var newTotal = totalShares + n;
            var pct      = ((n / newTotal) * 100).toFixed(2);
            var parts    = ['Ownership: <strong>' + pct + '%</strong> (after add)'];
            if (sharePrice > 0) {
                var val = (n * sharePrice).toLocaleString('en-EG', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                parts.push('Value: <strong>L.E ' + val + '</strong>');
            }
            preview.innerHTML = parts.join(' &nbsp;·&nbsp; ');
            preview.classList.remove('hidden');
        };
    })();
    </script>
</x-admin::layouts>
