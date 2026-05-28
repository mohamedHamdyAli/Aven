<x-admin::layouts>
    <x-slot:title>
        Loyalty Points
    </x-slot>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            Loyalty Points
        </p>
    </div>

    <div class="mt-6 box-shadow rounded-xl bg-white p-6 dark:bg-gray-900">
        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-50 p-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div x-data="loyaltyIndex()" x-init="load()">
            <table class="w-full table-auto border-collapse">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300">Customer</th>
                        <th class="py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300">Email</th>
                        <th class="py-3 text-right text-sm font-semibold text-gray-600 dark:text-gray-300">Balance (pts)</th>
                        <th class="py-3 text-right text-sm font-semibold text-gray-600 dark:text-gray-300">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="row in rows" :key="row.id">
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                            <td class="py-3 text-sm text-gray-800 dark:text-gray-200" x-text="row.customer_name"></td>
                            <td class="py-3 text-sm text-gray-500 dark:text-gray-400" x-text="row.email"></td>
                            <td class="py-3 text-right text-sm font-semibold text-indigo-600" x-text="parseFloat(row.balance).toFixed(2)"></td>
                            <td class="py-3 text-right">
                                <button
                                    @click="openAdjust(row)"
                                    class="rounded bg-indigo-600 px-3 py-1 text-xs font-medium text-white hover:bg-indigo-700"
                                >
                                    Adjust
                                </button>
                            </td>
                        </tr>
                    </template>
                    <template x-if="rows.length === 0 && !loading">
                        <tr>
                            <td colspan="4" class="py-8 text-center text-sm text-gray-400">No loyalty records found.</td>
                        </tr>
                    </template>
                    <template x-if="loading">
                        <tr>
                            <td colspan="4" class="py-8 text-center text-sm text-gray-400">Loading...</td>
                        </tr>
                    </template>
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="mt-4 flex items-center justify-between text-sm text-gray-500" x-show="total > 0">
                <span>Total: <span x-text="total"></span> customers</span>
                <div class="flex gap-2">
                    <button
                        @click="page > 1 && (page-- , load())"
                        :disabled="page <= 1"
                        class="rounded border px-3 py-1 disabled:opacity-40"
                    >Prev</button>
                    <span>Page <span x-text="page"></span> of <span x-text="lastPage"></span></span>
                    <button
                        @click="page < lastPage && (page++, load())"
                        :disabled="page >= lastPage"
                        class="rounded border px-3 py-1 disabled:opacity-40"
                    >Next</button>
                </div>
            </div>

            <!-- Adjust Modal -->
            <template x-if="adjustRow">
                <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
                    <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl dark:bg-gray-900">
                        <h2 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">
                            Adjust Points — <span x-text="adjustRow.customer_name" class="text-indigo-600"></span>
                        </h2>
                        <form :action="`{{ url('admin/marketing/loyalty') }}/${adjustRow.customer_id}/adjust`" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Points (positive = add, negative = subtract)</label>
                                <input
                                    type="number"
                                    name="points"
                                    step="0.01"
                                    required
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                />
                            </div>
                            <div class="mb-4">
                                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Note (optional)</label>
                                <input
                                    type="text"
                                    name="note"
                                    maxlength="255"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                />
                            </div>
                            <div class="flex justify-end gap-3">
                                <button type="button" @click="adjustRow = null" class="rounded-lg border border-gray-300 px-4 py-2 text-sm hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-800">Cancel</button>
                                <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </template>
        </div>
    </div>

    @pushOnce('scripts')
    <script>
    function loyaltyIndex() {
        return {
            rows: [],
            loading: false,
            page: 1,
            total: 0,
            lastPage: 1,
            adjustRow: null,
            load() {
                this.loading = true;
                fetch(`{{ url('admin/marketing/loyalty') }}?page=${this.page}`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                }).then(r => r.json()).then(d => {
                    this.rows = d.data || [];
                    this.total = d.total || 0;
                    this.lastPage = d.last_page || 1;
                    this.loading = false;
                }).catch(() => { this.loading = false; });
            },
            openAdjust(row) {
                this.adjustRow = row;
            }
        };
    }
    </script>
    @endPushOnce
</x-admin::layouts>
