@php
    use Webkul\CostManagement\Models\ProductCost;

    $locale  = app()->getLocale();
    $channel = core()->getCurrentChannelCode();

    $isConfigurable = $product->type === 'configurable';

    if ($isConfigurable) {
        // Build variant list with name, price, and existing cost
        $variants = $product->variants->map(function ($v) use ($locale, $channel) {
            $flat = DB::table('product_flat')
                ->where('product_id', $v->id)
                ->where('locale', $locale)
                ->where('channel', $channel)
                ->first();

            $cost = ProductCost::where('product_id', $v->id)->first();

            return (object) [
                'id'    => $v->id,
                'sku'   => $v->sku,
                'name'  => $flat->name ?? $v->sku,
                'price' => (float) ($flat->price ?? 0),
                'cost'  => $cost,
            ];
        });
    } else {
        $cost = ProductCost::where('product_id', $product->id)->first();
    }
@endphp

<div class="box-shadow rounded bg-white p-4 dark:bg-gray-900" id="cost-panel">
    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
        Product Cost & Margin
    </p>

    @if ($isConfigurable)
        {{-- ===== CONFIGURABLE: bulk apply + per-variant accordion ===== --}}

        {{-- Bulk Apply Section --}}
        <div id="cp-bulk-bar" class="mb-4 rounded-lg border border-blue-200 bg-blue-50 dark:border-blue-800 dark:bg-blue-950 hidden">
            <div class="flex items-center justify-between px-3 py-2 border-b border-blue-200 dark:border-blue-800">
                <span class="text-xs font-semibold text-blue-700 dark:text-blue-300">
                    Apply to <span id="cp-sel-count">0</span> selected variant(s)
                </span>
                <button type="button" id="cp-bulk-clear" class="text-xs text-blue-500 hover:underline">Clear selection</button>
            </div>
            <div class="grid grid-cols-2 gap-2 p-3">
                <div>
                    <label class="mb-1 block text-xs text-gray-500">Cost Price</label>
                    <input type="number" step="0.01" min="0" id="cp-bulk-cost_price"
                           class="w-full rounded border border-gray-300 px-2 py-1.5 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                           placeholder="0.00">
                </div>
                <div>
                    <label class="mb-1 block text-xs text-gray-500">Mfg. Fee</label>
                    <input type="number" step="0.01" min="0" id="cp-bulk-manufacturing_fee"
                           class="w-full rounded border border-gray-300 px-2 py-1.5 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                           placeholder="0.00">
                </div>
                <div>
                    <label class="mb-1 block text-xs text-gray-500">Shipping / Unit</label>
                    <input type="number" step="0.01" min="0" id="cp-bulk-shipping_cost_per_unit"
                           class="w-full rounded border border-gray-300 px-2 py-1.5 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                           placeholder="0.00">
                </div>
                <div>
                    <label class="mb-1 block text-xs text-gray-500">Other</label>
                    <input type="number" step="0.01" min="0" id="cp-bulk-other_costs"
                           class="w-full rounded border border-gray-300 px-2 py-1.5 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                           placeholder="0.00">
                </div>
            </div>
            <div class="px-3 pb-3">
                <button type="button" id="cp-bulk-apply"
                        class="w-full rounded bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-700 disabled:opacity-50">
                    Apply to Selected
                </button>
                <p id="cp-bulk-ok"  class="mt-1 hidden text-xs text-green-600"></p>
                <p id="cp-bulk-err" class="mt-1 hidden text-xs text-red-600"></p>
            </div>
        </div>

        {{-- Select-All row --}}
        <div class="mb-2 flex items-center gap-2 px-1">
            <input type="checkbox" id="cp-select-all" class="h-3.5 w-3.5 cursor-pointer rounded border-gray-400">
            <label for="cp-select-all" class="cursor-pointer text-xs text-gray-500 select-none">Select all variants</label>
        </div>

        {{-- Variant accordion list --}}
        <div class="grid gap-4">
            @foreach ($variants as $variant)
                @php
                    $totalCost = $variant->cost
                        ? (float)$variant->cost->cost_price + (float)$variant->cost->manufacturing_fee
                          + (float)$variant->cost->shipping_cost_per_unit + (float)$variant->cost->other_costs
                        : 0;
                    $margin = $variant->price > 0
                        ? round((($variant->price - $totalCost) / $variant->price) * 100, 1)
                        : null;
                    $markerColor = is_null($margin) ? 'bg-gray-300'
                        : ($margin >= 30 ? 'bg-green-500' : ($margin >= 10 ? 'bg-yellow-500' : 'bg-red-500'));
                @endphp

                <div class="rounded-lg border border-gray-200 dark:border-gray-700"
                     data-variant-id="{{ $variant->id }}"
                     data-update-url="{{ route('admin.cost_management.products.update', $variant->id) }}">

                    {{-- Variant header --}}
                    <div class="flex items-center rounded-t-lg hover:bg-gray-50 dark:hover:bg-gray-800">
                        {{-- Checkbox --}}
                        <label class="flex cursor-pointer items-center px-3 py-2.5">
                            <input type="checkbox"
                                   class="cp-variant-check h-3.5 w-3.5 rounded border-gray-400"
                                   data-variant-id="{{ $variant->id }}">
                        </label>

                        <button type="button"
                                class="cp-toggle flex flex-1 items-center justify-between py-2.5 pr-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">
                            <span class="flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full {{ $markerColor }}"></span>
                                {{ $variant->name }}
                                <span class="text-xs text-gray-400 font-normal">{{ $variant->sku }}</span>
                            </span>
                            <span class="flex items-center gap-2 text-xs text-gray-500">
                                @if (! is_null($margin))
                                    <span class="{{ $margin >= 30 ? 'text-green-600' : ($margin >= 10 ? 'text-yellow-600' : 'text-red-600') }} font-semibold">
                                        {{ $margin }}%
                                    </span>
                                @endif
                                <svg class="cp-chevron h-4 w-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </span>
                        </button>
                    </div>

                    {{-- Variant cost fields (collapsed by default) --}}
                    <div class="cp-body hidden px-3 pb-3 pt-2">
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="mb-1 block text-xs text-gray-500">Cost Price</label>
                                <input type="number" step="0.01" min="0" data-field="cost_price"
                                       class="cp-field w-full rounded border border-gray-300 px-2 py-1.5 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                       value="{{ $variant->cost->cost_price ?? '' }}" placeholder="0.00">
                            </div>
                            <div>
                                <label class="mb-1 block text-xs text-gray-500">Mfg. Fee</label>
                                <input type="number" step="0.01" min="0" data-field="manufacturing_fee"
                                       class="cp-field w-full rounded border border-gray-300 px-2 py-1.5 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                       value="{{ $variant->cost->manufacturing_fee ?? '' }}" placeholder="0.00">
                            </div>
                            <div>
                                <label class="mb-1 block text-xs text-gray-500">Shipping / Unit</label>
                                <input type="number" step="0.01" min="0" data-field="shipping_cost_per_unit"
                                       class="cp-field w-full rounded border border-gray-300 px-2 py-1.5 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                       value="{{ $variant->cost->shipping_cost_per_unit ?? '' }}" placeholder="0.00">
                            </div>
                            <div>
                                <label class="mb-1 block text-xs text-gray-500">Other</label>
                                <input type="number" step="0.01" min="0" data-field="other_costs"
                                       class="cp-field w-full rounded border border-gray-300 px-2 py-1.5 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                       value="{{ $variant->cost->other_costs ?? '' }}" placeholder="0.00">
                            </div>
                        </div>

                        <div class="cp-live-summary mt-2 text-xs text-gray-500 hidden">
                            Total: <span class="cp-live-total font-semibold text-gray-700 dark:text-gray-200"></span>
                            @if ($variant->price > 0)
                                &nbsp;|&nbsp; Margin: <span class="cp-live-margin font-semibold"></span>
                            @endif
                        </div>

                        <button type="button"
                                class="cp-save-btn mt-2.5 w-full rounded bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-700 disabled:opacity-50"
                                data-price="{{ $variant->price }}">
                            Save
                        </button>
                        <p class="cp-ok mt-1 hidden text-xs text-green-600">✓ Saved</p>
                        <p class="cp-err mt-1 hidden text-xs text-red-600"></p>
                    </div>
                </div>
            @endforeach
        </div>

    @else
        {{-- ===== SIMPLE PRODUCT ===== --}}
        @php
            $totalCost = $cost
                ? (float)$cost->cost_price + (float)$cost->manufacturing_fee
                  + (float)$cost->shipping_cost_per_unit + (float)$cost->other_costs
                : 0;
        @endphp

        <div data-variant-id="{{ $product->id }}"
             data-update-url="{{ route('admin.cost_management.products.update', $product->id) }}">
            <div class="grid gap-3">
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-400">Cost Price (EGP)</label>
                    <input type="number" step="0.01" min="0" data-field="cost_price"
                           class="cp-field w-full rounded-md border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                           value="{{ $cost->cost_price ?? '' }}" placeholder="0.00">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-400">Manufacturing Fee</label>
                    <input type="number" step="0.01" min="0" data-field="manufacturing_fee"
                           class="cp-field w-full rounded-md border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                           value="{{ $cost->manufacturing_fee ?? '' }}" placeholder="0.00">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-400">Shipping / Unit</label>
                    <input type="number" step="0.01" min="0" data-field="shipping_cost_per_unit"
                           class="cp-field w-full rounded-md border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                           value="{{ $cost->shipping_cost_per_unit ?? '' }}" placeholder="0.00">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-400">Other Costs</label>
                    <input type="number" step="0.01" min="0" data-field="other_costs"
                           class="cp-field w-full rounded-md border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                           value="{{ $cost->other_costs ?? '' }}" placeholder="0.00">
                </div>
            </div>

            <div class="cp-live-summary mt-3 rounded-md bg-gray-50 px-3 py-2 text-xs dark:bg-gray-800 {{ $totalCost > 0 ? '' : 'hidden' }}">
                Total: <span class="cp-live-total font-semibold text-gray-800 dark:text-white"></span>
            </div>

            <button type="button"
                    class="cp-save-btn mt-3 w-full rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-50"
                    data-price="0">
                Save Cost
            </button>
            <p class="cp-ok mt-2 hidden text-xs text-green-600">✓ Cost saved successfully.</p>
            <p class="cp-err mt-2 hidden text-xs text-red-600"></p>
        </div>
    @endif
</div>

@pushOnce('scripts')
<script>
(function () {
    function getXsrf() {
        var m = document.cookie.match(/(?:^|;\s*)XSRF-TOKEN=([^;]*)/);
        return m ? decodeURIComponent(m[1]) : '';
    }

    function fields(container) {
        return {
            cost_price:             parseFloat(container.querySelector('[data-field="cost_price"]')?.value)             || 0,
            manufacturing_fee:      parseFloat(container.querySelector('[data-field="manufacturing_fee"]')?.value)      || 0,
            shipping_cost_per_unit: parseFloat(container.querySelector('[data-field="shipping_cost_per_unit"]')?.value) || 0,
            other_costs:            parseFloat(container.querySelector('[data-field="other_costs"]')?.value)            || 0,
        };
    }

    function updateLive(container) {
        var f       = fields(container);
        var total   = f.cost_price + f.manufacturing_fee + f.shipping_cost_per_unit + f.other_costs;
        var price   = parseFloat(container.querySelector('.cp-save-btn')?.dataset.price) || 0;
        var summary = container.querySelector('.cp-live-summary');
        var totalEl = container.querySelector('.cp-live-total');
        var marginEl= container.querySelector('.cp-live-margin');

        if (total > 0 && summary) {
            totalEl.textContent = 'L.E ' + total.toLocaleString('en-EG', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            if (marginEl && price > 0) {
                var m = Math.round(((price - total) / price) * 100 * 10) / 10;
                marginEl.textContent = m + '%';
                marginEl.className = 'cp-live-margin font-semibold ' + (m >= 30 ? 'text-green-600' : m >= 10 ? 'text-yellow-600' : 'text-red-600');
            }
            summary.classList.remove('hidden');
        } else if (summary) {
            summary.classList.add('hidden');
        }
    }

    function putCost(url, payload) {
        return fetch(url, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-XSRF-TOKEN': getXsrf(),
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify(payload),
        }).then(function(r) { if (!r.ok) throw new Error('Error ' + r.status); return r.json(); });
    }

    function saveCost(container) {
        var url  = container.closest('[data-update-url]').dataset.updateUrl;
        var btn  = container.querySelector('.cp-save-btn');
        var okEl = container.querySelector('.cp-ok');
        var erEl = container.querySelector('.cp-err');

        btn.disabled = true;
        okEl.classList.add('hidden');
        erEl.classList.add('hidden');

        putCost(url, fields(container))
        .then(function() {
            okEl.classList.remove('hidden');
            setTimeout(function() { okEl.classList.add('hidden'); }, 3000);
        })
        .catch(function(e) { erEl.textContent = e.message; erEl.classList.remove('hidden'); })
        .finally(function() { btn.disabled = false; });
    }

    // ── Bulk Apply ──────────────────────────────────────────────────────────────
    // All element lookups are done fresh at call time (no init-time caching)
    // so timing relative to DOM render order doesn't matter.

    function bulkFields() {
        return {
            cost_price:             parseFloat(document.getElementById('cp-bulk-cost_price')?.value)             || 0,
            manufacturing_fee:      parseFloat(document.getElementById('cp-bulk-manufacturing_fee')?.value)      || 0,
            shipping_cost_per_unit: parseFloat(document.getElementById('cp-bulk-shipping_cost_per_unit')?.value) || 0,
            other_costs:            parseFloat(document.getElementById('cp-bulk-other_costs')?.value)            || 0,
        };
    }

    function checkedBoxes() {
        return Array.from(document.querySelectorAll('#cost-panel .cp-variant-check:checked'));
    }

    function updateBulkBar() {
        var bar      = document.getElementById('cp-bulk-bar');
        var countEl  = document.getElementById('cp-sel-count');
        var saEl     = document.getElementById('cp-select-all');
        if (!bar) return;

        var checked = checkedBoxes();
        var all     = document.querySelectorAll('#cost-panel .cp-variant-check');

        if (checked.length > 0) {
            if (countEl) countEl.textContent = checked.length;
            bar.classList.remove('hidden');
        } else {
            bar.classList.add('hidden');
        }

        if (saEl) {
            saEl.indeterminate = checked.length > 0 && checked.length < all.length;
            saEl.checked       = all.length > 0 && checked.length === all.length;
        }
    }

    function clearSelection() {
        document.querySelectorAll('#cost-panel .cp-variant-check').forEach(function(cb) { cb.checked = false; });
        var saEl = document.getElementById('cp-select-all');
        if (saEl) { saEl.checked = false; saEl.indeterminate = false; }
        updateBulkBar();
    }

    // Checkbox changes — event delegation
    document.addEventListener('change', function(e) {
        if (e.target.id === 'cp-select-all') {
            var state = e.target.checked;
            document.querySelectorAll('#cost-panel .cp-variant-check').forEach(function(cb) { cb.checked = state; });
            updateBulkBar();
            return;
        }
        if (e.target.classList.contains('cp-variant-check')) {
            updateBulkBar();
        }
    });

    // Clear & Apply — event delegation (no init-time binding)
    document.addEventListener('click', function(e) {
        if (e.target.closest('#cp-bulk-clear')) {
            clearSelection();
            return;
        }

        if (e.target.closest('#cp-bulk-apply')) {
            var applyBtn = document.getElementById('cp-bulk-apply');
            var okEl     = document.getElementById('cp-bulk-ok');
            var errEl    = document.getElementById('cp-bulk-err');
            var checked  = checkedBoxes();
            if (checked.length === 0) return;

            var payload = bulkFields();
            applyBtn.disabled = true;
            if (okEl)  okEl.classList.add('hidden');
            if (errEl) errEl.classList.add('hidden');

            var savedCount = checked.length;
            var promises = checked.map(function(cb) {
                var card = cb.closest('[data-update-url]');
                var url  = card.dataset.updateUrl;

                ['cost_price', 'manufacturing_fee', 'shipping_cost_per_unit', 'other_costs'].forEach(function(f) {
                    if (payload[f] > 0) {
                        var el = card.querySelector('[data-field="' + f + '"]');
                        if (el) el.value = payload[f];
                    }
                });

                return putCost(url, payload);
            });

            Promise.allSettled(promises).then(function(results) {
                var failed = results.filter(function(r) { return r.status === 'rejected'; });
                if (failed.length === 0) {
                    if (okEl) {
                        okEl.textContent = '✓ Applied to ' + savedCount + ' variant(s) successfully.';
                        okEl.classList.remove('hidden');
                        setTimeout(function() { okEl.classList.add('hidden'); }, 4000);
                    }
                    clearSelection();
                } else {
                    if (errEl) {
                        errEl.textContent = failed.length + ' variant(s) failed to save. Please try again.';
                        errEl.classList.remove('hidden');
                    }
                }
                applyBtn.disabled = false;
            });
        }
    });

    // ── Accordion toggle ─────────────────────────────────────────────────────────
    document.addEventListener('click', function(e) {
        var toggle = e.target.closest('.cp-toggle');
        if (toggle) {
            // .cp-body is a sibling of the header flex-div, not of the button itself
            var card    = toggle.closest('[data-update-url]');
            var body    = card ? card.querySelector('.cp-body') : null;
            var chevron = toggle.querySelector('.cp-chevron');
            if (!body) return;
            var isOpen  = !body.classList.contains('hidden');
            body.classList.toggle('hidden', isOpen);
            if (chevron) chevron.style.transform = isOpen ? '' : 'rotate(180deg)';
        }
    });

    // Live update on input
    document.addEventListener('input', function(e) {
        if (!e.target.classList.contains('cp-field')) return;
        var container = e.target.closest('[data-update-url], .cp-body, #cost-panel > div');
        if (container) updateLive(container);
    });

    // Per-variant Save button
    document.addEventListener('click', function(e) {
        var btn = e.target.closest('.cp-save-btn');
        if (!btn) return;
        var container = btn.closest('.cp-body') || btn.closest('[data-update-url]');
        if (container) saveCost(container);
    });
})();
</script>
@endPushOnce
