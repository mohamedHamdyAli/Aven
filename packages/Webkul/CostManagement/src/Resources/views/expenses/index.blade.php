<x-admin::layouts>
    <x-slot:title>General Expenses</x-slot>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800">General Expenses</p>
        <button
            type="button"
            class="primary-button"
            @click="$refs.addModal.open()"
        >
            + Add Expense
        </button>
    </div>

    <x-admin::datagrid :src="route('admin.cost_management.expenses.index')" />

    <!-- Add Expense Modal -->
    <x-admin::modal ref="addModal">
        <x-slot:header>
            <p class="text-lg font-semibold">Add New Expense</p>
        </x-slot>

        <x-slot:content>
            <form id="add-expense-form" method="POST" action="{{ route('admin.cost_management.expenses.store') }}">
                @csrf
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" required
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm"
                               placeholder="e.g. Monthly Rent">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Category <span class="text-red-500">*</span></label>
                        <select name="category" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                            @foreach ($categories as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Amount <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" min="0" name="amount" required
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm"
                               placeholder="0.00">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Date <span class="text-red-500">*</span></label>
                        <input type="date" name="expense_date" required value="{{ date('Y-m-d') }}"
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Recurring?</label>
                        <select name="is_recurring" id="is-recurring-sel"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                            <option value="0">No — One-time</option>
                            <option value="1">Yes — Recurring</option>
                        </select>
                    </div>

                    <div id="frequency-row" class="hidden">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Frequency</label>
                        <select name="frequency" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                            <option value="monthly">Monthly</option>
                            <option value="weekly">Weekly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Notes</label>
                        <textarea name="notes" rows="2"
                                  class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm"
                                  placeholder="Optional notes..."></textarea>
                    </div>
                </div>
            </form>
        </x-slot>

        <x-slot:footer>
            <button type="submit" form="add-expense-form" class="primary-button">Save Expense</button>
        </x-slot>
    </x-admin::modal>

    @push('scripts')
    <script>
        document.getElementById('is-recurring-sel').addEventListener('change', function () {
            document.getElementById('frequency-row').classList.toggle('hidden', this.value === '0');
        });
    </script>
    @endpush
</x-admin::layouts>
