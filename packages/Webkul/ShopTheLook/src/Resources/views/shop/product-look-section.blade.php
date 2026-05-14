@php
    $lookItems = \Webkul\ShopTheLook\Models\ProductLookItem::with('lookProduct.productFlat')
        ->where('product_id', $product->id)
        ->orderBy('sort_order')
        ->get()
        ->map(fn($item) => [
            'id'    => $item->look_product_id,
            'name'  => $item->lookProduct?->productFlat?->name ?? $item->lookProduct?->sku ?? '',
            'sku'   => $item->lookProduct?->sku ?? '',
            'price' => $item->lookProduct?->getTypeInstance()?->getMinimalPrice() ?? 0,
            'image' => $item->lookProduct?->base_image?->url ?? null,
            'url'   => $item->lookProduct?->url_key
                        ? route('shop.product_or_category.index', $item->lookProduct->url_key)
                        : null,
            'saleable' => (bool) $item->lookProduct?->isSaleable(1),
        ])
        ->filter(fn($p) => $p['name'] !== '');
@endphp

@if ($lookItems->isNotEmpty())
<div class="container mt-10 px-[60px] max-1180:px-5 max-sm:px-4" id="stl-section">
    <h2 class="mb-5 text-2xl font-semibold text-gray-800 dark:text-white max-sm:text-xl">
        Complete The Look
    </h2>

    <div class="flex flex-wrap gap-4">
        @foreach ($lookItems as $item)
        <div class="flex w-[160px] flex-col gap-2 max-sm:w-[calc(50%-8px)]"
             data-stl-id="{{ $item['id'] }}"
             data-stl-saleable="{{ $item['saleable'] ? '1' : '0' }}">

            <!-- Image -->
            <div class="relative overflow-hidden rounded-xl border border-gray-100 dark:border-gray-700">
                @if ($item['image'])
                    <a href="{{ $item['url'] ?? '#' }}" target="_blank">
                        <img src="{{ $item['image'] }}"
                             alt="{{ $item['name'] }}"
                             class="h-[160px] w-full object-cover transition hover:scale-105 max-sm:h-[130px]">
                    </a>
                @else
                    <div class="h-[160px] w-full bg-gray-100 dark:bg-gray-800 max-sm:h-[130px]"></div>
                @endif

                @if ($item['saleable'])
                <label class="absolute bottom-2 right-2 flex h-6 w-6 cursor-pointer items-center justify-center rounded-full border-2 border-blue-500 bg-white shadow"
                       title="أضف للاختيار">
                    <input type="checkbox"
                           class="stl-check hidden"
                           data-id="{{ $item['id'] }}"
                           checked>
                    <span class="stl-check-icon block h-3 w-3 rounded-full bg-blue-500 transition"></span>
                </label>
                @endif
            </div>

            <!-- Info -->
            <div>
                <p class="truncate text-sm font-medium text-gray-800 dark:text-white">{{ $item['name'] }}</p>
                <p class="text-sm text-blue-600">
                    {!! core()->formatPrice($item['price']) !!}
                </p>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Add All to Cart -->
    <div class="mt-5 flex items-center gap-4">
        <button type="button"
                id="stl-add-all"
                class="rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 max-sm:w-full max-sm:py-2.5">
            Add Selected to Cart
        </button>
        <p id="stl-cart-msg" class="hidden text-sm text-green-600">✓ Added to cart!</p>
    </div>
</div>

@pushOnce('scripts')
<script>
(function () {
    /* Toggle checkbox icon */
    document.querySelectorAll('.stl-check').forEach(function (chk) {
        chk.addEventListener('change', function () {
            var icon = chk.nextElementSibling;
            if (chk.checked) {
                icon.classList.remove('bg-gray-300');
                icon.classList.add('bg-blue-500');
            } else {
                icon.classList.remove('bg-blue-500');
                icon.classList.add('bg-gray-300');
            }
        });
    });

    document.getElementById('stl-add-all').addEventListener('click', function () {
        var btn  = this;
        var msg  = document.getElementById('stl-cart-msg');
        var ids  = [];

        document.querySelectorAll('.stl-check:checked').forEach(function (chk) {
            ids.push(chk.dataset.id);
        });

        if (!ids.length) return;

        btn.disabled = true;
        var promises = ids.map(function (id) {
            var fd = new FormData();
            fd.append('product_id', id);
            fd.append('quantity', 1);
            return fetch('{{ route("shop.api.checkout.cart.store") }}', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: fd,
            });
        });

        Promise.allSettled(promises).then(function () {
            msg.classList.remove('hidden');
            btn.disabled = false;
            /* update mini-cart badge if app emitter available */
            if (window.app && window.app.config && window.app._context) {
                fetch('{{ route("shop.api.checkout.cart.index") }}')
                    .then(function(r){ return r.json(); })
                    .then(function(d){
                        if (d && d.data) {
                            document.querySelectorAll('[data-cart-count]').forEach(function(el){
                                el.textContent = d.data.items_count || 0;
                            });
                        }
                    });
            }
            setTimeout(function () { msg.classList.add('hidden'); }, 3000);
        });
    });
})();
</script>
@endPushOnce
@endif
