{{-- Shop The Look — admin panel (right sidebar of product edit) --}}
<div class="box-shadow relative rounded bg-white p-4 dark:bg-gray-900"
     id="stl-panel"
     data-product-id="{{ $product->id }}"
     data-items-url="{{ route('admin.shop-the-look.items',  $product->id) }}"
     data-search-url="{{ route('admin.shop-the-look.search', $product->id) }}"
     data-sync-url="{{ route('admin.shop-the-look.sync',   $product->id) }}">

    <p class="mb-3 text-base font-semibold text-gray-800 dark:text-white">
        🛍️ Shop The Look
    </p>
    <p class="mb-3 text-xs text-gray-400">
        أضف المنتجات اللي بتكمّل الأوت فيت دا — العميل هيقدر يضيفهم كلهم في الكارت
    </p>

    {{-- Search --}}
    <div class="relative mb-3">
        <input type="text" id="stl-search"
               placeholder="ابحث عن منتج..."
               autocomplete="off"
               class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white">
        <ul id="stl-results"
            class="absolute z-50 mt-1 hidden max-h-56 w-full overflow-y-auto rounded-md border border-gray-200 bg-white text-sm shadow-lg dark:border-gray-700 dark:bg-gray-900">
        </ul>
    </div>

    {{-- Selected items --}}
    <div id="stl-items" class="mb-3 flex min-h-[40px] flex-col gap-2"></div>

    {{-- Save --}}
    <button type="button" id="stl-save"
            class="w-full rounded-md bg-blue-600 px-4 py-1.5 text-sm font-semibold text-white hover:bg-blue-700">
        حفظ الأوت فيت
    </button>

    <p id="stl-msg" class="mt-2 hidden text-center text-xs text-green-600">✓ تم الحفظ</p>
</div>

@pushOnce('scripts')
<script>
(function () {
    var panel      = document.getElementById('stl-panel');
    if (!panel) return;

    var productId  = panel.dataset.productId;
    var itemsUrl   = panel.dataset.itemsUrl;
    var searchUrl  = panel.dataset.searchUrl;
    var syncUrl    = panel.dataset.syncUrl;
    var searchEl   = document.getElementById('stl-search');
    var resultsList= document.getElementById('stl-results');
    var itemsEl    = document.getElementById('stl-items');
    var saveBtn    = document.getElementById('stl-save');
    var msgEl      = document.getElementById('stl-msg');

    var selected = {}; // id => {id, name, sku, image}

    /* ── Load existing items ── */
    fetch(itemsUrl, { headers: { Accept: 'application/json' } })
        .then(function (r) { return r.json(); })
        .then(function (items) {
            items.forEach(function (p) { selected[p.id] = p; renderItems(); });
        });

    /* ── Render selected list ── */
    function renderItems() {
        itemsEl.innerHTML = '';
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
            itemsEl.appendChild(row);
        });
    }

    /* ── Remove ── */
    document.addEventListener('click', function (e) {
        if (!e.target.classList.contains('stl-remove')) return;
        delete selected[e.target.dataset.id];
        renderItems();
    });

    /* ── Search ── */
    var timer = null;
    searchEl.addEventListener('input', function () {
        clearTimeout(timer);
        var q = searchEl.value.trim();
        timer = setTimeout(function () { doSearch(q); }, 250);
    });

    function doSearch(q) {
        fetch(searchUrl + '?q=' + encodeURIComponent(q), { headers: { Accept: 'application/json' } })
            .then(function (r) { return r.json(); })
            .then(function (items) {
                resultsList.innerHTML = '';
                if (!items.length) {
                    resultsList.innerHTML = '<li class="px-3 py-2 text-xs text-gray-400">مفيش نتايج</li>';
                    resultsList.classList.remove('hidden');
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
                        resultsList.classList.add('hidden');
                        searchEl.value = '';
                    });
                    resultsList.appendChild(li);
                });
                resultsList.classList.remove('hidden');
            });
    }

    document.addEventListener('click', function (e) {
        if (!resultsList.contains(e.target) && e.target !== searchEl) resultsList.classList.add('hidden');
    });
    searchEl.addEventListener('focus', function () { if (searchEl.value.trim()) doSearch(searchEl.value.trim()); }, true);

    /* ── Save ── */
    saveBtn.addEventListener('click', function () {
        var ids = Object.keys(selected);
        var xsrf = (document.cookie.match(/(?:^|;\s*)XSRF-TOKEN=([^;]*)/) || [])[1];
        xsrf = xsrf ? decodeURIComponent(xsrf) : '';

        saveBtn.disabled = true;
        fetch(syncUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-XSRF-TOKEN': xsrf },
            body: JSON.stringify({ product_ids: ids }),
        })
        .then(function (r) { return r.json(); })
        .then(function () {
            msgEl.classList.remove('hidden');
            setTimeout(function () { msgEl.classList.add('hidden'); }, 2500);
        })
        .finally(function () { saveBtn.disabled = false; });
    });
})();
</script>
@endPushOnce
