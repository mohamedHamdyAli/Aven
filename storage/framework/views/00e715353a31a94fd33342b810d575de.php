<?php
    $locale = app()->getLocale();
    $lookItems = \Webkul\ShopTheLook\Models\ProductLookItem::with('lookProduct.product_flats')
        ->where('product_id', $product->id)
        ->orderBy('sort_order')
        ->get()
        ->map(fn($item) => [
            'id'    => $item->look_product_id,
            'name'  => $item->lookProduct?->product_flats?->firstWhere('locale', $locale)?->name
                       ?? $item->lookProduct?->product_flats?->first()?->name
                       ?? $item->lookProduct?->sku ?? '',
            'sku'   => $item->lookProduct?->sku ?? '',
            'price' => $item->lookProduct?->getTypeInstance()?->getMinimalPrice() ?? 0,
            'image' => $item->lookProduct ? (product_image()->getProductBaseImage($item->lookProduct)['small_image_url'] ?? null) : null,
            'url'   => $item->lookProduct?->url_key
                        ? route('shop.product_or_category.index', $item->lookProduct->url_key)
                        : null,
            'saleable' => (bool) $item->lookProduct?->isSaleable(1),
        ])
        ->filter(fn($p) => $p['name'] !== '');
?>

<?php if($lookItems->isNotEmpty()): ?>
<div id="stl-section">
    <h2 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">
        Complete The Look
    </h2>

    <div class="flex flex-wrap gap-3">
        <?php $__currentLoopData = $lookItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="flex w-[130px] flex-col gap-2 max-sm:w-[calc(50%-6px)]"
             data-stl-id="<?php echo e($item['id']); ?>"
             data-stl-saleable="<?php echo e($item['saleable'] ? '1' : '0'); ?>">

            <!-- Image -->
            <div class="relative overflow-hidden rounded-xl border border-gray-100 dark:border-gray-700">
                <?php if($item['image']): ?>
                    <a href="<?php echo e($item['url'] ?? '#'); ?>" target="_blank">
                        <img src="<?php echo e($item['image']); ?>"
                             alt="<?php echo e($item['name']); ?>"
                             class="h-[130px] w-full object-cover transition hover:scale-105 max-sm:h-[110px]">
                    </a>
                <?php else: ?>
                    <div class="h-[130px] w-full bg-gray-100 dark:bg-gray-800 max-sm:h-[110px]"></div>
                <?php endif; ?>

                <?php if($item['saleable']): ?>
                <label class="absolute bottom-2 right-2 flex h-6 w-6 cursor-pointer items-center justify-center rounded-full border-2 border-blue-500 bg-white shadow"
                       title="أضف للاختيار">
                    <input type="checkbox"
                           class="stl-check hidden"
                           data-id="<?php echo e($item['id']); ?>"
                           checked>
                    <span class="stl-check-icon block h-3 w-3 rounded-full bg-blue-500 transition"></span>
                </label>
                <?php endif; ?>
            </div>

            <!-- Info -->
            <div>
                <p class="truncate text-sm font-medium text-gray-800 dark:text-white"><?php echo e($item['name']); ?></p>
                <p class="text-sm text-blue-600">
                    <?php echo core()->formatPrice($item['price']); ?>

                </p>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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

<?php if (! $__env->hasRenderedOnce('07f869c0-b2e3-476f-95aa-012575994aef')): $__env->markAsRenderedOnce('07f869c0-b2e3-476f-95aa-012575994aef');
$__env->startPush('scripts'); ?>
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
            return fetch('<?php echo e(route("shop.api.checkout.cart.store")); ?>', {
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
                fetch('<?php echo e(route("shop.api.checkout.cart.index")); ?>')
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
<?php $__env->stopPush(); endif; ?>
<?php endif; ?>
<?php /**PATH D:\aven\packages\Webkul\ShopTheLook\src\Providers/../Resources/views/shop/product-look-section.blade.php ENDPATH**/ ?>