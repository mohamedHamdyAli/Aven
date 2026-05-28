<x-admin::layouts>
    <x-slot:title>Shareholders</x-slot>

    <div class="flex flex-col gap-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xl font-bold text-gray-800 dark:text-white">Shareholders</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Total allocated:
                    <span class="{{ $totalPercentage > 100 ? 'text-red-600 font-bold' : ($totalPercentage == 100 ? 'text-green-600 font-bold' : 'text-yellow-600 font-bold') }}">
                        {{ number_format($totalPercentage, 2) }}%
                    </span>
                    / 100%
                </p>
            </div>
            <a href="{{ route('admin.cost_management.distributions.index') }}" class="primary-button">
                Profit Distributions
            </a>
        </div>

        {{-- Success / Error --}}
        @if(session('success'))
            <div class="rounded-lg bg-green-50 p-4 text-sm text-green-700 border border-green-200">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="rounded-lg bg-red-50 p-4 text-sm text-red-700 border border-red-200">{{ $errors->first() }}</div>
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- Shareholders List --}}
            <div class="lg:col-span-2 flex flex-col gap-3">
                @forelse($shareholders as $sh)
                    <div class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-900">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-center gap-3">
                                {{-- Avatar --}}
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

                            <div class="text-right">
                                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($sh->percentage, 2) }}%</p>
                                <p class="text-xs text-gray-400">{{ $sh->active ? 'Active' : 'Inactive' }}</p>
                                <p class="text-sm font-semibold text-green-600 mt-1">
                                    Total earned: {{ core()->currency($sh->totalEarned()) }}
                                </p>
                            </div>
                        </div>

                        {{-- Progress bar --}}
                        <div class="mt-3 h-2 w-full rounded-full bg-gray-100 dark:bg-gray-800">
                            <div class="h-2 rounded-full bg-blue-500" style="width: {{ min($sh->percentage, 100) }}%"></div>
                        </div>

                        {{-- Edit form --}}
                        <details class="mt-3">
                            <summary class="cursor-pointer text-xs text-blue-600 hover:underline">Edit</summary>
                            <form method="POST" action="{{ route('admin.cost_management.shareholders.update', $sh->id) }}" class="mt-3 grid grid-cols-2 gap-3">
                                @csrf @method('PUT')
                                <input type="text" name="name" value="{{ $sh->name }}" placeholder="Name" required class="input-field col-span-2">
                                <input type="email" name="email" value="{{ $sh->email }}" placeholder="Email" class="input-field">
                                <input type="text" name="phone" value="{{ $sh->phone }}" placeholder="Phone" class="input-field">
                                <input type="number" name="percentage" value="{{ $sh->percentage }}" step="0.01" min="0.01" max="100" placeholder="%" required class="input-field">
                                <input type="date" name="joined_at" value="{{ $sh->joined_at?->toDateString() }}" class="input-field">
                                <label class="flex items-center gap-2 col-span-2 text-sm text-gray-600">
                                    <input type="checkbox" name="active" value="1" {{ $sh->active ? 'checked' : '' }}>
                                    Active
                                </label>
                                <textarea name="notes" placeholder="Notes" class="input-field col-span-2 h-16">{{ $sh->notes }}</textarea>
                                <div class="col-span-2 flex gap-2">
                                    <button type="submit" class="primary-button text-sm">Save</button>
                                    <form method="POST" action="{{ route('admin.cost_management.shareholders.destroy', $sh->id) }}" class="inline" onsubmit="return confirm('Delete this shareholder?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="rounded-lg border border-red-300 bg-red-50 px-3 py-1.5 text-sm text-red-600 hover:bg-red-100">Delete</button>
                                    </form>
                                </div>
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
                <form method="POST" action="{{ route('admin.cost_management.shareholders.store') }}" class="flex flex-col gap-3">
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
                        <label class="label-field">Ownership % *</label>
                        <div class="relative">
                            <input type="number" name="percentage" step="0.01" min="0.01" max="100" required class="input-field w-full pr-8" placeholder="25.00">
                            <span class="absolute inset-y-0 right-3 flex items-center text-gray-400">%</span>
                        </div>
                        <p class="mt-1 text-xs text-gray-400">Remaining: {{ number_format(max(0, 100 - $totalPercentage), 2) }}%</p>
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
</x-admin::layouts>
