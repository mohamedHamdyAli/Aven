<x-admin::layouts>
    <x-slot:title>
        @lang('egypt-shipping::app.admin.egypt-shipping.title')
    </x-slot>

    <div class="flex items-center justify-between mb-6">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('egypt-shipping::app.admin.egypt-shipping.title')
        </p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-sm text-gray-500 dark:text-gray-400">@lang('egypt-shipping::app.admin.egypt-shipping.stats.total')</p>
            <p class="text-3xl font-bold text-gray-800 dark:text-white">{{ $stats['total'] }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-sm text-gray-500 dark:text-gray-400">@lang('egypt-shipping::app.admin.egypt-shipping.stats.active')</p>
            <p class="text-3xl font-bold text-green-600">{{ $stats['active'] }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-sm text-gray-500 dark:text-gray-400">@lang('egypt-shipping::app.admin.egypt-shipping.stats.priced')</p>
            <p class="text-3xl font-bold text-blue-600">{{ $stats['priced'] }}</p>
        </div>
    </div>

    {{-- Bulk Action Bar --}}
    <div
        id="bulk-bar"
        class="mb-4 flex flex-wrap items-center gap-3 rounded-lg border border-blue-200 bg-blue-50 px-5 py-3 dark:border-blue-800 dark:bg-blue-900/20"
    >
        {{-- Select All --}}
        <label class="flex cursor-pointer items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300">
            <input
                type="checkbox"
                id="select-all"
                class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
            >
            @lang('egypt-shipping::app.admin.egypt-shipping.select-all')
        </label>

        <span class="text-gray-300 dark:text-gray-600">|</span>

        <span id="selected-count" class="text-sm text-blue-700 dark:text-blue-400 font-medium min-w-[80px]">
            @lang('egypt-shipping::app.admin.egypt-shipping.none-selected')
        </span>

        <div class="flex items-center gap-2 ltr:ml-auto rtl:mr-auto">
            <input
                type="number"
                id="bulk-rate"
                min="0"
                step="0.01"
                placeholder="@lang('egypt-shipping::app.admin.egypt-shipping.bulk-rate-placeholder')"
                class="w-36 rounded-md border border-gray-300 px-3 py-1.5 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white"
            >
            <button
                id="bulk-apply-btn"
                class="rounded-md bg-blue-600 px-4 py-1.5 text-sm font-semibold text-white hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50 transition"
                disabled
            >
                @lang('egypt-shipping::app.admin.egypt-shipping.bulk-apply')
            </button>
        </div>

        <div id="bulk-feedback" class="hidden text-sm font-medium text-green-600 dark:text-green-400"></div>
    </div>

    {{-- Governorates Grid --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3" id="governorates-grid">
        @foreach ($governorates as $gov)
            <div
                class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 transition gov-card"
                id="gov-card-{{ $gov->id }}"
                data-id="{{ $gov->id }}"
            >
                <div class="flex items-start justify-between mb-3">
                    {{-- Checkbox --}}
                    <label class="flex cursor-pointer items-center gap-2 mt-0.5">
                        <input
                            type="checkbox"
                            class="gov-checkbox h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                            data-id="{{ $gov->id }}"
                        >
                    </label>

                    <div class="flex-1 mx-2">
                        <p class="font-semibold text-gray-800 dark:text-white">{{ $gov->name_ar }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $gov->name_en }}</p>
                    </div>

                    {{-- Active Toggle --}}
                    <label class="relative inline-flex cursor-pointer items-center">
                        <input
                            type="checkbox"
                            class="peer sr-only gov-toggle"
                            data-id="{{ $gov->id }}"
                            {{ $gov->is_active ? 'checked' : '' }}
                        >
                        <div class="peer h-6 w-11 rounded-full bg-gray-200 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-blue-600 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none dark:border-gray-600 dark:bg-gray-700 rtl:peer-checked:after:-translate-x-full"></div>
                    </label>
                </div>

                <div class="flex items-center gap-2">
                    <input
                        type="number"
                        min="0"
                        step="0.01"
                        class="gov-rate-input w-full rounded-md border border-gray-200 px-3 py-1.5 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                        data-id="{{ $gov->id }}"
                        value="{{ $gov->rate ?? '' }}"
                        placeholder="@lang('egypt-shipping::app.admin.egypt-shipping.rate-placeholder')"
                    >
                    <button
                        class="gov-save-btn rounded-md bg-blue-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-blue-700 transition"
                        data-id="{{ $gov->id }}"
                    >
                        @lang('egypt-shipping::app.admin.egypt-shipping.save')
                    </button>
                </div>

                <p class="gov-feedback-{{ $gov->id }} mt-1 hidden text-xs text-green-600"></p>
            </div>
        @endforeach
    </div>

    @push('scripts')
        <script>
        (function () {
            var adminUrl = @json(url(config('app.admin_url', 'admin').'/egypt-shipping'));
            var bulkUrl  = @json(route('admin.egypt-shipping.bulk-update'));

            /* ── helpers ── */
            function getChecked() {
                return Array.prototype.slice.call(document.querySelectorAll('.gov-checkbox:checked'));
            }

            function bulkRateVal() {
                var el = document.getElementById('bulk-rate');
                return el ? el.value : '';
            }

            function canApply() {
                return getChecked().length > 0 && bulkRateVal() !== '';
            }

            function updateBulkBar() {
                var checked    = getChecked();
                var count      = checked.length;
                var total      = document.querySelectorAll('.gov-checkbox').length;
                var countLabel = document.getElementById('selected-count');
                var applyBtn   = document.getElementById('bulk-apply-btn');
                var selectAll  = document.getElementById('select-all');

                if (countLabel) {
                    countLabel.textContent = count > 0 ? count + ' محافظة محددة' : 'لا يوجد محدد';
                }

                if (applyBtn) applyBtn.disabled = !canApply();

                document.querySelectorAll('.gov-card').forEach(function (card) {
                    var cb = card.querySelector('.gov-checkbox');
                    if (cb && cb.checked) {
                        card.classList.add('ring-2', 'ring-blue-400');
                    } else {
                        card.classList.remove('ring-2', 'ring-blue-400');
                    }
                });

                if (selectAll) {
                    selectAll.indeterminate = count > 0 && count < total;
                    selectAll.checked       = total > 0 && count === total;
                }
            }

            /* ── XSRF token from cookie (no meta tag in admin layout) ── */
            function xsrfToken() {
                var m = document.cookie.match(/(?:^|;\s*)XSRF-TOKEN=([^;]*)/);
                return m ? decodeURIComponent(m[1]) : '';
            }

            function jsonHeaders() {
                return { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-XSRF-TOKEN': xsrfToken() };
            }

            /* ── single save ── */
            function saveGovernorate(id, rate, isActive) {
                var payload = { is_active: isActive ? 1 : 0 };
                if (rate !== '') payload.rate = parseFloat(rate);

                fetch(adminUrl + '/' + id, {
                    method: 'PUT',
                    headers: jsonHeaders(),
                    body: JSON.stringify(payload),
                })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (data.success) {
                        var fb = document.querySelector('.gov-feedback-' + id);
                        if (fb) {
                            fb.textContent = 'تم الحفظ ✓';
                            fb.classList.remove('hidden');
                            setTimeout(function () { fb.classList.add('hidden'); }, 2000);
                        }
                    }
                });
            }

            /* ── bulk apply ── */
            function doBulkApply() {
                var rate     = bulkRateVal();
                var ids      = getChecked().map(function (cb) { return cb.dataset.id; });
                var applyBtn = document.getElementById('bulk-apply-btn');
                var bulkFb   = document.getElementById('bulk-feedback');

                if (!ids.length) { alert('اختر محافظة على الأقل'); return; }
                if (rate === '')  { alert('أدخل السعر أولاً'); return; }

                if (applyBtn) { applyBtn.disabled = true; applyBtn.textContent = '...'; }

                fetch(bulkUrl, {
                    method: 'POST',
                    headers: jsonHeaders(),
                    body: JSON.stringify({ ids: ids, rate: parseFloat(rate) }),
                })
                .then(function (r) {
                    if (!r.ok) {
                        return r.text().then(function (t) { throw new Error(r.status + ': ' + t.substring(0, 150)); });
                    }
                    return r.json();
                })
                .then(function (data) {
                    if (data.success) {
                        ids.forEach(function (id) {
                            var input = document.querySelector('.gov-rate-input[data-id="' + id + '"]');
                            if (input) input.value = parseFloat(rate).toFixed(2);
                        });
                        if (bulkFb) {
                            bulkFb.textContent = '✓ تم التحديث (' + data.updated + ' محافظة)';
                            bulkFb.classList.remove('hidden');
                            bulkFb.style.color = '';
                            setTimeout(function () { bulkFb.classList.add('hidden'); }, 3000);
                        }
                    }
                    if (applyBtn) { applyBtn.disabled = !canApply(); applyBtn.textContent = 'تطبيق على المحددة'; }
                })
                .catch(function (err) {
                    if (bulkFb) {
                        bulkFb.textContent = 'خطأ: ' + (err.message || 'فشل الاتصال');
                        bulkFb.classList.remove('hidden');
                        bulkFb.style.color = 'red';
                    }
                    if (applyBtn) { applyBtn.disabled = !canApply(); applyBtn.textContent = 'تطبيق على المحددة'; }
                });
            }

            /* ── event delegation (survives Vue mount) ── */
            document.addEventListener('change', function (e) {
                if (e.target.id === 'select-all') {
                    document.querySelectorAll('.gov-checkbox').forEach(function (cb) {
                        cb.checked = e.target.checked;
                    });
                    updateBulkBar();
                    return;
                }

                if (e.target.classList.contains('gov-checkbox')) {
                    updateBulkBar();
                    return;
                }

                if (e.target.classList.contains('gov-toggle')) {
                    var id   = e.target.dataset.id;
                    var rate = document.querySelector('.gov-rate-input[data-id="' + id + '"]');
                    saveGovernorate(id, rate ? rate.value : '', e.target.checked);
                    return;
                }

                if (e.target.id === 'bulk-rate') {
                    var applyBtn = document.getElementById('bulk-apply-btn');
                    if (applyBtn) applyBtn.disabled = !canApply();
                }
            });

            document.addEventListener('input', function (e) {
                if (e.target.id === 'bulk-rate') {
                    var applyBtn = document.getElementById('bulk-apply-btn');
                    if (applyBtn) applyBtn.disabled = !canApply();
                }
            });

            document.addEventListener('click', function (e) {
                var saveBtn  = e.target.closest('.gov-save-btn');
                var applyBtn = e.target.closest('#bulk-apply-btn');

                if (saveBtn) {
                    var id     = saveBtn.dataset.id;
                    var rate   = document.querySelector('.gov-rate-input[data-id="' + id + '"]');
                    var active = document.querySelector('.gov-toggle[data-id="' + id + '"]');
                    saveGovernorate(id, rate ? rate.value : '', active ? active.checked : false);
                    return;
                }

                if (applyBtn && !applyBtn.disabled) {
                    doBulkApply();
                }
            });
        })();
        </script>
    @endpush
</x-admin::layouts>
