<?php $__env->startSection('title'); ?>
    Create Flash Sale
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="flex gap-4 justify-between items-center">
        <p class="text-xl font-bold text-gray-800 dark:text-white">Create Flash Sale</p>
    </div>

    <div class="mt-6 box-shadow rounded-xl bg-white dark:bg-gray-900 p-6 max-w-2xl">
        <?php if($errors->any()): ?>
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                <ul class="list-disc list-inside">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form
            method="POST"
            action="<?php echo e(route('admin.marketing.flash-sales.store')); ?>"
        >
            <?php echo csrf_field(); ?>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                <input
                    type="text"
                    name="name"
                    value="<?php echo e(old('name')); ?>"
                    class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-navyBlue dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                    required
                />
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Discount %</label>
                <input
                    type="number"
                    name="discount_percent"
                    value="<?php echo e(old('discount_percent', 10)); ?>"
                    min="1"
                    max="99"
                    step="0.01"
                    class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-navyBlue dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                    required
                />
            </div>

            <div class="mb-4 grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Starts At</label>
                    <input
                        type="datetime-local"
                        name="starts_at"
                        value="<?php echo e(old('starts_at')); ?>"
                        class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-navyBlue dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                        required
                    />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ends At</label>
                    <input
                        type="datetime-local"
                        name="ends_at"
                        value="<?php echo e(old('ends_at')); ?>"
                        class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-navyBlue dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                        required
                    />
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Products <span class="text-gray-400">(search and select)</span>
                </label>
                <input
                    type="text"
                    id="product-search"
                    placeholder="Search products by name or SKU..."
                    class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-navyBlue dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                />
                <div id="search-results" class="mt-1 border border-gray-200 rounded-lg hidden max-h-48 overflow-y-auto dark:border-gray-600"></div>
                <div id="selected-products" class="mt-3 flex flex-wrap gap-2"></div>
                <p class="text-xs text-gray-400 mt-1">Click on a product to add it to the flash sale.</p>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="primary-button">Create Flash Sale</button>
                <a href="<?php echo e(route('admin.marketing.flash-sales.index')); ?>" class="secondary-button">Cancel</a>
            </div>
        </form>
    </div>

    <?php if (! $__env->hasRenderedOnce('4f523b38-8fa2-420f-a986-180b0a3df9a9')): $__env->markAsRenderedOnce('4f523b38-8fa2-420f-a986-180b0a3df9a9');
$__env->startPush('scripts'); ?>
        <script>
        (function () {
            const searchInput = document.getElementById('product-search');
            const resultsBox  = document.getElementById('search-results');
            const selectedBox = document.getElementById('selected-products');
            const selected    = {};

            let timer;

            searchInput.addEventListener('input', function () {
                clearTimeout(timer);
                const q = this.value.trim();
                if (q.length < 2) { resultsBox.classList.add('hidden'); return; }
                timer = setTimeout(() => fetchProducts(q), 300);
            });

            function fetchProducts(q) {
                fetch('<?php echo e(route('admin.catalog.products.search')); ?>?query=' + encodeURIComponent(q))
                    .then(r => r.json())
                    .then(data => {
                        const items = data.data || data;
                        if (!items.length) { resultsBox.innerHTML = '<p class="px-3 py-2 text-sm text-gray-400">No products found.</p>'; resultsBox.classList.remove('hidden'); return; }
                        resultsBox.innerHTML = items.slice(0, 20).map(p =>
                            `<div class="px-3 py-2 cursor-pointer hover:bg-gray-50 text-sm dark:hover:bg-gray-700" data-id="${p.id}" data-name="${p.name} (${p.sku})">${p.name} <span class="text-gray-400">${p.sku}</span></div>`
                        ).join('');
                        resultsBox.classList.remove('hidden');
                        resultsBox.querySelectorAll('[data-id]').forEach(el => el.addEventListener('click', function () {
                            addProduct(this.dataset.id, this.dataset.name);
                            resultsBox.classList.add('hidden');
                            searchInput.value = '';
                        }));
                    });
            }

            function addProduct(id, name) {
                if (selected[id]) return;
                selected[id] = name;

                const chip = document.createElement('div');
                chip.className = 'flex items-center gap-1 rounded-full bg-navyBlue/10 text-navyBlue px-3 py-1 text-sm';
                chip.innerHTML = `<span>${name}</span><button type="button" class="ml-1 text-navyBlue hover:text-red-500" onclick="removeProduct('${id}', this.parentElement)">×</button><input type="hidden" name="product_ids[]" value="${id}">`;
                selectedBox.appendChild(chip);
            }

            window.removeProduct = function(id, el) {
                delete selected[id];
                el.remove();
            };
        })();
        </script>
    <?php $__env->stopPush(); endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin::layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\aven\packages\Webkul\FlashSale\src\Resources\views\admin\flash-sales\create.blade.php ENDPATH**/ ?>