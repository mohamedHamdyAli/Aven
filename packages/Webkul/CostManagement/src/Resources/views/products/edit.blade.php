<x-admin::layouts>
    <x-slot:title>Edit Product Cost — {{ $product->name ?? $product->sku }}</x-slot>

    <div class="flex items-center justify-between gap-4">
        <p class="text-xl font-bold text-gray-800">
            Edit Cost: <span class="text-indigo-600">{{ $product->name ?? $product->sku }}</span>
        </p>
        <a href="{{ route('admin.cost_management.products.index') }}"
           class="secondary-button">
            ← Back to List
        </a>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">

        <!-- Form -->
        <div class="lg:col-span-2">
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-5 text-base font-semibold text-gray-700">Cost Breakdown</h2>

                <form method="POST" action="{{ route('admin.cost_management.products.update', $product->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                Purchase / Manufacturing Cost <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="number" step="0.01" min="0"
                                name="cost_price"
                                value="{{ old('cost_price', $cost->cost_price ?? 0) }}"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="0.00"
                                required
                            >
                            @error('cost_price') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">Manufacturing Fee</label>
                            <input
                                type="number" step="0.01" min="0"
                                name="manufacturing_fee"
                                value="{{ old('manufacturing_fee', $cost->manufacturing_fee ?? 0) }}"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="0.00"
                            >
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">Shipping Cost / Unit</label>
                            <input
                                type="number" step="0.01" min="0"
                                name="shipping_cost_per_unit"
                                value="{{ old('shipping_cost_per_unit', $cost->shipping_cost_per_unit ?? 0) }}"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="0.00"
                            >
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">Other Costs / Unit</label>
                            <input
                                type="number" step="0.01" min="0"
                                name="other_costs"
                                value="{{ old('other_costs', $cost->other_costs ?? 0) }}"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="0.00"
                            >
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Notes</label>
                        <textarea
                            name="notes"
                            rows="3"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Optional notes about this product's costs..."
                        >{{ old('notes', $cost->notes ?? '') }}</textarea>
                    </div>

                    <div class="mt-6 flex items-center gap-4">
                        <button type="submit" class="primary-button">
                            Save Costs
                        </button>
                        <a href="{{ route('admin.cost_management.products.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Live Summary -->
        <div>
            <div class="rounded-xl border border-indigo-100 bg-indigo-50 p-5 shadow-sm">
                <h3 class="mb-4 text-sm font-semibold text-indigo-800">Live Calculation</h3>

                @php
                    $sellingPrice = $product->getTypeInstance()->getMinimalPrice() ?? 0;
                    $totalCost    = ($cost->cost_price ?? 0) + ($cost->manufacturing_fee ?? 0) + ($cost->shipping_cost_per_unit ?? 0) + ($cost->other_costs ?? 0);
                    $profit       = $sellingPrice - $totalCost;
                    $margin       = $sellingPrice > 0 ? round(($profit / $sellingPrice) * 100, 1) : 0;
                @endphp

                <div id="cost-summary">
                    <div class="mb-3 flex justify-between text-sm">
                        <span class="text-gray-600">Selling Price</span>
                        <span class="font-semibold text-gray-900">{{ core()->formatPrice($sellingPrice) }}</span>
                    </div>
                    <div class="mb-3 flex justify-between text-sm">
                        <span class="text-gray-600">Total Cost</span>
                        <span id="s-total-cost" class="font-semibold text-red-600">{{ core()->formatPrice($totalCost) }}</span>
                    </div>
                    <div class="mb-1 border-t border-indigo-200 pt-3 flex justify-between text-sm font-bold">
                        <span class="text-indigo-800">Profit / Unit</span>
                        <span id="s-profit" class="{{ $profit >= 0 ? 'text-green-700' : 'text-red-700' }}">
                            {{ core()->formatPrice($profit) }}
                        </span>
                    </div>
                    <div class="mt-3 text-center">
                        <span class="text-3xl font-bold {{ $margin >= 30 ? 'text-green-600' : ($margin >= 10 ? 'text-yellow-600' : 'text-red-600') }}" id="s-margin">
                            {{ $margin }}%
                        </span>
                        <p class="text-xs text-gray-500">Profit Margin</p>
                    </div>
                </div>
            </div>

            <div class="mt-4 rounded-xl border border-gray-200 bg-white p-4 text-xs text-gray-500">
                <p class="font-semibold text-gray-700 mb-1">SKU: {{ $product->sku }}</p>
                <p>Selling price is the product's current minimum price. Update product price in the Products section.</p>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const sellingPrice = {{ (float) $sellingPrice }};
        const inputs = document.querySelectorAll('input[name="cost_price"], input[name="manufacturing_fee"], input[name="shipping_cost_per_unit"], input[name="other_costs"]');

        function recalc() {
            let total = 0;
            inputs.forEach(i => total += parseFloat(i.value || 0));
            const profit = sellingPrice - total;
            const margin = sellingPrice > 0 ? ((profit / sellingPrice) * 100).toFixed(1) : 0;

            document.getElementById('s-total-cost').textContent = total.toFixed(2);
            document.getElementById('s-profit').textContent     = profit.toFixed(2);
            document.getElementById('s-margin').textContent     = margin + '%';

            const mEl = document.getElementById('s-margin');
            mEl.className = 'text-3xl font-bold ' + (margin >= 30 ? 'text-green-600' : (margin >= 10 ? 'text-yellow-600' : 'text-red-600'));
        }

        inputs.forEach(i => i.addEventListener('input', recalc));
    </script>
    @endpush
</x-admin::layouts>
