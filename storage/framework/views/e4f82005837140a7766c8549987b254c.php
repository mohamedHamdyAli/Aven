
<div class="box-shadow relative rounded bg-white p-4 dark:bg-gray-900"
     id="stl-panel"
     data-product-id="<?php echo e($product->id); ?>"
     data-items-url="<?php echo e(route('admin.shop-the-look.items',  $product->id)); ?>"
     data-search-url="<?php echo e(route('admin.shop-the-look.search', $product->id)); ?>"
     data-sync-url="<?php echo e(route('admin.shop-the-look.sync',   $product->id)); ?>">

    <p class="mb-3 text-base font-semibold text-gray-800 dark:text-white">
        🛍️ Shop The Look
    </p>
    <p class="mb-3 text-xs text-gray-400">
        أضف المنتجات اللي بتكمّل الأوت فيت دا — العميل هيقدر يضيفهم كلهم في الكارت
    </p>

    
    <div class="mb-3">
        <input type="text" id="stl-search"
               placeholder="ابحث عن منتج..."
               autocomplete="off"
               class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white">
        <ul id="stl-results"
            class="mt-1 hidden max-h-56 w-full overflow-y-auto rounded-md border border-gray-200 bg-white text-sm shadow-lg dark:border-gray-700 dark:bg-gray-900">
        </ul>
    </div>

    
    <div id="stl-items" class="mb-3 flex min-h-[40px] flex-col gap-2"></div>

    
    <button type="button" id="stl-save"
            class="w-full rounded-md bg-blue-600 px-4 py-1.5 text-sm font-semibold text-white hover:bg-blue-700">
        حفظ الأوت فيت
    </button>

    <p id="stl-msg" class="mt-2 hidden text-center text-xs text-green-600">✓ تم الحفظ</p>
</div>

<?php if (! $__env->hasRenderedOnce('732fb636-0891-4767-a9e5-e34f9b377515')): $__env->markAsRenderedOnce('732fb636-0891-4767-a9e5-e34f9b377515');
$__env->startPush('scripts'); ?>
<script>
(function () {
    /* URLs come from the panel's data attributes — re-read on each use so
       Vue re-renders don't break us. */
    function panel()       { return document.getElementById('stl-panel'); }
    function searchEl()    { return document.getElementById('stl-search'); }
    function resultsList() { return document.getElementById('stl-results'); }
    function itemsEl()     { return document.getElementById('stl-items'); }
    function saveBtn()     { return document.getElementById('stl-save'); }
    function msgEl()       { return document.getElementById('stl-msg'); }

    function getUrl(key) {
        var p = panel();
        return p ? p.dataset[key] : null;
    }

    var selected = {}; // id => {id, name, sku, image}
    var searchTimer = null;

    /* ── Load existing items on first paint ── */
    function loadItems() {
        var url = getUrl('itemsUrl');
        if (!url) return;
        fetch(url, { credentials: 'same-origin', headers: { Accept: 'application/json' } })
            .then(function (r) { return r.json(); })
            .then(function (items) {
                items.forEach(function (p) { selected[p.id] = p; });
                renderItems();
            })
            .catch(function () {});
    }

    /* ── Render selected list ── */
    function renderItems() {
        var el = itemsEl();
        if (!el) return;
        el.innerHTML = '';
        Object.values(selected).forEach(function (p) {
            var row = document.createElement('div');
            row.className = 'flex items-center gap-2 rounded-md border border-gray-100 p-1.5 dark:border-gray-700';
            row.innerHTML =
                (p.image
                    ? '<img src="'+p.image+'" class="h-9 w-9 rounded object-cover border border-gray-200" onerror="this.style.display=\'none\'">'
                    : '<div class="h-9 w-9 flex-shrink-0 rounded bg-gray-100 dark:bg-gray-700"></div>')
                + '<div class="min-w-0 flex-1"><p class="truncate text-xs font-semibold text-gray-800 dark:text-white">'+(p.name||p.sku)+'</p>'
                + '<p class="text-[10px] text-gray-400">'+p.sku+'</p></div>'
                + '<button type="button" data-id="'+p.id+'" class="stl-remove ml-auto text-red-400 hover:text-red-600 text-lg leading-none font-bold">×</button>';
            el.appendChild(row);
        });
    }

    /* ── Search ── */
    function doSearch(q) {
        var url = getUrl('searchUrl');
        if (!url) return;
        fetch(url + '?q=' + encodeURIComponent(q), { credentials: 'same-origin', headers: { Accept: 'application/json' } })
            .then(function (r) { return r.json(); })
            .then(function (items) {
                var list = resultsList();
                if (!list) return;
                list.innerHTML = '';
                if (!items.length) {
                    list.innerHTML = '<li class="px-3 py-2 text-xs text-gray-400">مفيش نتايج</li>';
                    list.classList.remove('hidden');
                    return;
                }
                items.forEach(function (p) {
                    if (selected[p.id]) return;
                    var li = document.createElement('li');
                    li.className = 'flex cursor-pointer items-center justify-between px-3 py-2 hover:bg-blue-50 dark:hover:bg-gray-800';
                    li.innerHTML = '<span class="font-medium text-gray-800 dark:text-white text-xs">'+(p.name||p.sku)+'</span>'
                        + '<span class="text-xs text-gray-400 ml-2">'+p.sku+'</span>';
                    li.addEventListener('mousedown', function (ev) {
                        ev.preventDefault();
                        selected[p.id] = { id: p.id, name: p.name, sku: p.sku, image: null };
                        renderItems();
                        var list2 = resultsList(); if (list2) list2.classList.add('hidden');
                        var s = searchEl(); if (s) s.value = '';
                    });
                    list.appendChild(li);
                });
                list.classList.remove('hidden');
            })
            .catch(function () {});
    }

    /* ── Save ── */
    function saveItems() {
        var url = getUrl('syncUrl');
        var btn = saveBtn();
        var msg = msgEl();
        if (!url || !btn) return;

        var ids = Object.keys(selected);
        var xsrf = (document.cookie.match(/(?:^|;\s*)XSRF-TOKEN=([^;]*)/) || [])[1];
        xsrf = xsrf ? decodeURIComponent(xsrf) : '';

        btn.disabled = true;
        fetch(url, {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-XSRF-TOKEN': xsrf },
            body: JSON.stringify({ product_ids: ids }),
        })
        .then(function (r) { return r.json(); })
        .then(function () {
            if (msg) { msg.classList.remove('hidden'); setTimeout(function () { msg.classList.add('hidden'); }, 2500); }
        })
        .catch(function () {})
        .finally(function () { btn.disabled = false; });
    }

    /* ── Event delegation — works even after Vue re-renders ── */
    document.addEventListener('input', function (e) {
        if (e.target.id !== 'stl-search') return;
        clearTimeout(searchTimer);
        var q = e.target.value.trim();
        searchTimer = setTimeout(function () { doSearch(q); }, 250);
    });

    document.addEventListener('focus', function (e) {
        if (e.target.id !== 'stl-search') return;
        var q = e.target.value.trim();
        if (q) doSearch(q);
    }, true);

    document.addEventListener('click', function (e) {
        /* Remove item */
        if (e.target.classList.contains('stl-remove')) {
            delete selected[e.target.dataset.id];
            renderItems();
            return;
        }
        /* Save */
        if (e.target.id === 'stl-save') {
            saveItems();
            return;
        }
        /* Hide results when clicking outside */
        var list = resultsList();
        var s = searchEl();
        if (list && !list.contains(e.target) && e.target !== s) {
            list.classList.add('hidden');
        }
    });

    /* ── Init ── */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', loadItems);
    } else {
        loadItems();
    }
})();
</script>
<?php $__env->stopPush(); endif; ?>
<?php /**PATH D:\aven\packages\Webkul\ShopTheLook\src\Resources\views\admin\product-look-panel.blade.php ENDPATH**/ ?>