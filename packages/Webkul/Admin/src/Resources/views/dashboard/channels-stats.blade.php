<v-dashboard-channels-stats>
    <!-- Shimmer -->
    <div class="flex flex-col gap-4 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
        <div class="shimmer h-5 w-40 rounded"></div>
        <div class="flex flex-col gap-3">
            @for ($i = 0; $i < 2; $i++)
                <div class="shimmer h-20 w-full rounded-lg"></div>
            @endfor
        </div>
    </div>
</v-dashboard-channels-stats>

@pushOnce('scripts')
    <script type="text/x-template" id="v-dashboard-channels-stats-template">
        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-gray-200 p-4 dark:border-gray-800">
                <p class="text-base font-semibold text-gray-800 dark:text-white">
                    @lang('admin::app.dashboard.index.channels-stats.title')
                </p>
            </div>

            <!-- Loading shimmer -->
            <template v-if="isLoading">
                <div class="flex flex-col gap-3 p-4">
                    <div v-for="i in 2" :key="i" class="shimmer h-[88px] w-full rounded-lg"></div>
                </div>
            </template>

            <!-- Channel cards -->
            <template v-else>
                <div class="flex flex-col divide-y divide-gray-100 dark:divide-gray-800">
                    <div
                        v-for="channel in channels"
                        :key="channel.id"
                        class="p-4"
                    >
                        <!-- Channel name + Add Spend button -->
                        <div class="mb-3 flex items-center justify-between">
                            <p class="font-semibold text-gray-800 dark:text-white">@{{ channel.name }}</p>

                            <button
                                type="button"
                                class="cursor-pointer rounded-md border border-blue-500 px-2.5 py-1 text-xs text-blue-500 transition-all hover:bg-blue-500 hover:text-white dark:border-blue-400 dark:text-blue-400"
                                @click="openSpendModal(channel)"
                            >
                                @lang('admin::app.dashboard.index.channels-stats.add-spend')
                            </button>
                        </div>

                        <!-- Stats grid -->
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                            <!-- Orders -->
                            <div class="rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    @lang('admin::app.dashboard.index.channels-stats.orders')
                                </p>
                                <p class="mt-1 text-lg font-bold text-gray-800 dark:text-white">
                                    @{{ channel.orders_count }}
                                </p>
                            </div>

                            <!-- Revenue -->
                            <div class="rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    @lang('admin::app.dashboard.index.channels-stats.revenue')
                                </p>
                                <p class="mt-1 text-lg font-bold text-gray-800 dark:text-white">
                                    @{{ channel.formatted_revenue }}
                                </p>
                            </div>

                            <!-- Ad Spend -->
                            <div class="rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    @lang('admin::app.dashboard.index.channels-stats.ad-spend')
                                </p>
                                <p class="mt-1 text-lg font-bold text-gray-800 dark:text-white">
                                    @{{ channel.formatted_ad_spend }}
                                </p>
                            </div>

                            <!-- ROAS -->
                            <div class="rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    @lang('admin::app.dashboard.index.channels-stats.roas')
                                </p>
                                <p
                                    class="mt-1 text-lg font-bold"
                                    :class="channel.roas ? (channel.roas >= 2 ? 'text-emerald-500' : 'text-red-500') : 'text-gray-400'"
                                >
                                    @{{ channel.roas ? channel.roas + 'x' : '@lang('admin::app.dashboard.index.channels-stats.no-roas')' }}
                                </p>
                            </div>
                        </div>

                        <!-- Cost per order (only when ad spend exists) -->
                        <p
                            v-if="channel.formatted_cost_per_order"
                            class="mt-2 text-xs text-gray-500 dark:text-gray-400"
                        >
                            @lang('admin::app.dashboard.index.channels-stats.cost-per-order'):
                            <span class="font-medium text-gray-700 dark:text-gray-300">@{{ channel.formatted_cost_per_order }}</span>
                        </p>
                    </div>
                </div>
            </template>
        </div>

        <!-- Ad Spend Modal -->
        <div
            v-if="modal.open"
            class="fixed inset-0 z-[999] flex items-center justify-center bg-black/50"
            @click.self="closeModal"
        >
            <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl dark:bg-gray-900">
                <div class="mb-4 flex items-center justify-between">
                    <p class="text-base font-semibold text-gray-800 dark:text-white">
                        @{{ modal.record ? '@lang('admin::app.dashboard.index.channels-stats.edit-spend')' : '@lang('admin::app.dashboard.index.channels-stats.add-spend')' }}
                        — @{{ modal.channel?.name }}
                    </p>
                    <button type="button" class="text-xl text-gray-400 hover:text-gray-600" @click="closeModal">✕</button>
                </div>

                <form @submit.prevent="saveSpend" class="flex flex-col gap-4">
                    <!-- Amount -->
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            @lang('admin::app.dashboard.index.channels-stats.amount')
                        </label>
                        <input
                            v-model="modal.form.amount"
                            type="number"
                            step="0.01"
                            min="0"
                            required
                            class="w-full rounded-md border px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300"
                        />
                    </div>

                    <!-- Dates -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                @lang('admin::app.dashboard.index.channels-stats.start-date')
                            </label>
                            <input
                                v-model="modal.form.start_date"
                                type="date"
                                required
                                class="w-full rounded-md border px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300"
                            />
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                @lang('admin::app.dashboard.index.channels-stats.end-date')
                            </label>
                            <input
                                v-model="modal.form.end_date"
                                type="date"
                                required
                                class="w-full rounded-md border px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300"
                            />
                        </div>
                    </div>

                    <!-- Source -->
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            @lang('admin::app.dashboard.index.channels-stats.source')
                        </label>
                        <select
                            v-model="modal.form.source"
                            class="w-full rounded-md border px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300"
                        >
                            <option value="manual">@lang('admin::app.dashboard.index.channels-stats.source-manual')</option>
                            <option value="auto">@lang('admin::app.dashboard.index.channels-stats.source-auto')</option>
                        </select>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            @lang('admin::app.dashboard.index.channels-stats.notes')
                        </label>
                        <input
                            v-model="modal.form.notes"
                            type="text"
                            class="w-full rounded-md border px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300"
                        />
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between gap-3 pt-2">
                        <button
                            v-if="modal.record"
                            type="button"
                            class="text-sm text-red-500 hover:underline"
                            @click="deleteSpend"
                        >
                            @lang('admin::app.dashboard.index.channels-stats.delete')
                        </button>

                        <div class="ml-auto flex gap-3">
                            <button
                                type="button"
                                class="rounded-md border px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300"
                                @click="closeModal"
                            >
                                @lang('admin::app.dashboard.index.channels-stats.cancel')
                            </button>
                            <button
                                type="submit"
                                :disabled="modal.saving"
                                class="rounded-md bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-700 disabled:opacity-50"
                            >
                                @lang('admin::app.dashboard.index.channels-stats.save')
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-dashboard-channels-stats', {
            template: '#v-dashboard-channels-stats-template',

            data() {
                return {
                    channels: [],
                    isLoading: true,
                    modal: {
                        open: false,
                        saving: false,
                        channel: null,
                        record: null,
                        form: {
                            amount: '',
                            start_date: '',
                            end_date: '',
                            source: 'manual',
                            notes: '',
                        },
                    },
                };
            },

            mounted() {
                this.getStats({});
                this.$emitter.on('reporting-filter-updated', this.getStats);
            },

            methods: {
                getStats(filters) {
                    this.isLoading = true;
                    const params = Object.assign({}, filters, { type: 'channels' });

                    this.$axios.get("{{ route('admin.dashboard.stats') }}", { params })
                        .then(response => {
                            this.channels = response.data.statistics;
                            this.isLoading = false;
                        })
                        .catch(() => { this.isLoading = false; });
                },

                openSpendModal(channel) {
                    this.modal.channel = channel;
                    this.modal.record  = null;
                    this.modal.form    = { amount: '', start_date: '', end_date: '', source: 'manual', notes: '' };
                    this.modal.open    = true;
                },

                closeModal() {
                    this.modal.open = false;
                },

                saveSpend() {
                    this.modal.saving = true;

                    const payload = {
                        ...this.modal.form,
                        channel_id: this.modal.channel.id,
                    };

                    const request = this.modal.record
                        ? this.$axios.put("{{ route('admin.channel-ad-spends.store') }}".replace('/channel-ad-spends', '/channel-ad-spends/' + this.modal.record.id), payload)
                        : this.$axios.post("{{ route('admin.channel-ad-spends.store') }}", payload);

                    request
                        .then(() => {
                            this.closeModal();
                            this.$emitter.emit('add-flash', { type: 'success', message: "@lang('admin::app.dashboard.index.channels-stats.spend-saved')" });
                            this.getStats({});
                        })
                        .catch(() => {})
                        .finally(() => { this.modal.saving = false; });
                },

                deleteSpend() {
                    if (! confirm("@lang('admin::app.dashboard.index.channels-stats.confirm-delete')")) return;

                    this.$axios.delete("{{ route('admin.channel-ad-spends.store') }}".replace('/channel-ad-spends', '/channel-ad-spends/' + this.modal.record.id))
                        .then(() => {
                            this.closeModal();
                            this.$emitter.emit('add-flash', { type: 'success', message: "@lang('admin::app.dashboard.index.channels-stats.spend-deleted')" });
                            this.getStats({});
                        })
                        .catch(() => {});
                },
            },
        });
    </script>
@endPushOnce
