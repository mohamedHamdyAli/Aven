<x-admin::layouts>
    <x-slot:title>Edit Expense</x-slot>

    <div class="flex items-center justify-between gap-4">
        <p class="text-xl font-bold text-gray-800">Edit Expense</p>
        <a href="{{ route('admin.cost_management.expenses.index') }}" class="secondary-button">← Back</a>
    </div>

    <div class="mt-6 max-w-2xl rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.cost_management.expenses.update', $expense->id) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $expense->title) }}" required
                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Category</label>
                    <select name="category" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                        @foreach ($categories as $key => $label)
                            <option value="{{ $key }}" {{ $expense->category === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Amount</label>
                    <input type="number" step="0.01" min="0" name="amount" value="{{ old('amount', $expense->amount) }}" required
                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Date</label>
                    <input type="date" name="expense_date" value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}" required
                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Recurring?</label>
                    <select name="is_recurring" id="is-recurring-sel" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                        <option value="0" {{ ! $expense->is_recurring ? 'selected' : '' }}>No — One-time</option>
                        <option value="1" {{ $expense->is_recurring ? 'selected' : '' }}>Yes — Recurring</option>
                    </select>
                </div>

                <div id="frequency-row" class="{{ $expense->is_recurring ? '' : 'hidden' }}">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Frequency</label>
                    <select name="frequency" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                        @foreach (['monthly', 'weekly', 'yearly'] as $freq)
                            <option value="{{ $freq }}" {{ $expense->frequency === $freq ? 'selected' : '' }}>{{ ucfirst($freq) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="notes" rows="3" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">{{ old('notes', $expense->notes) }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex gap-4">
                <button type="submit" class="primary-button">Update Expense</button>
                <a href="{{ route('admin.cost_management.expenses.index') }}" class="secondary-button">Cancel</a>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.getElementById('is-recurring-sel').addEventListener('change', function () {
            document.getElementById('frequency-row').classList.toggle('hidden', this.value === '0');
        });
    </script>
    @endpush
</x-admin::layouts>
