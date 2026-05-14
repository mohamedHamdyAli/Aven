<x-admin::layouts>
    <x-slot:title>
        {{ isset($chart) ? __('size-guide::app.admin.size-guide.edit') : __('size-guide::app.admin.size-guide.create') }}
    </x-slot>

    <div class="flex items-center justify-between mb-6">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            {{ isset($chart) ? __('size-guide::app.admin.size-guide.edit') : __('size-guide::app.admin.size-guide.create') }}
        </p>
        <a href="{{ route('admin.size-guide.index') }}" class="text-sm text-blue-600 hover:underline">
            ← @lang('size-guide::app.admin.size-guide.back')
        </a>
    </div>

    <form method="POST" enctype="multipart/form-data"
          action="{{ isset($chart) ? route('admin.size-guide.update', $chart->id) : route('admin.size-guide.store') }}">
        @csrf
        @if(isset($chart)) @method('PUT') @endif

        {{-- Basic Info --}}
        <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900 mb-6">
            <h3 class="mb-4 font-semibold text-gray-800 dark:text-white">@lang('size-guide::app.admin.size-guide.basic-info')</h3>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        @lang('size-guide::app.admin.size-guide.name') *
                    </label>
                    <input type="text" name="name" value="{{ old('name', $chart->name ?? '') }}"
                           class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-blue-500 focus:outline-none" required>
                    @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        @lang('size-guide::app.admin.size-guide.gender') *
                    </label>
                    <select name="gender" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-blue-500 focus:outline-none">
                        @foreach(['mens'=>'Mens','womens'=>'Womens','kids'=>'Kids','unisex'=>'Unisex'] as $val => $label)
                            <option value="{{ $val }}" {{ old('gender', $chart->gender ?? '') == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        @lang('size-guide::app.admin.size-guide.type') *
                    </label>
                    <select name="type" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-blue-500 focus:outline-none">
                        @foreach(['tops'=>'Tops','bottoms'=>'Bottoms','footwear'=>'Footwear','full-body'=>'Full Body','other'=>'Other'] as $val => $label)
                            <option value="{{ $val }}" {{ old('type', $chart->type ?? '') == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Size Guide Image --}}
        <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900 mb-6">
            <h3 class="mb-4 font-semibold text-gray-800 dark:text-white">
                @lang('size-guide::app.admin.size-guide.image')
            </h3>

            @if(isset($chart) && $chart->image)
            <div class="mb-4 flex items-start gap-4">
                <img src="{{ asset('storage/' . $chart->image) }}" alt="Size guide image"
                     class="h-40 rounded-lg border border-gray-200 object-contain bg-gray-50">
                <div>
                    <p class="text-sm text-gray-500 mb-2">@lang('size-guide::app.admin.size-guide.image-current')</p>
                    <label class="flex items-center gap-2 text-sm text-red-500 cursor-pointer">
                        <input type="checkbox" name="remove_image" value="1" class="rounded">
                        @lang('size-guide::app.admin.size-guide.image-remove')
                    </label>
                </div>
            </div>
            @endif

            <div id="sg-img-drop"
                 class="flex flex-col items-center justify-center gap-3 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 p-8 cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition dark:border-gray-600 dark:bg-gray-800"
                 onclick="document.getElementById('sg-img-input').click()">
                <svg class="h-10 w-10 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
                </svg>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    @lang('size-guide::app.admin.size-guide.image-upload-hint')
                </p>
                <p id="sg-img-name" class="text-xs text-blue-600 font-medium hidden"></p>
                <input type="file" id="sg-img-input" name="image" accept="image/*" class="hidden"
                       onchange="document.getElementById('sg-img-name').textContent=this.files[0]?.name;document.getElementById('sg-img-name').classList.remove('hidden');document.getElementById('sg-img-preview').src=URL.createObjectURL(this.files[0]);document.getElementById('sg-img-preview-wrap').classList.remove('hidden')">
            </div>

            <div id="sg-img-preview-wrap" class="mt-4 hidden">
                <p class="text-xs text-gray-500 mb-2">@lang('size-guide::app.admin.size-guide.image-preview')</p>
                <img id="sg-img-preview" src="" alt="" class="max-h-52 rounded-lg border border-gray-200 object-contain bg-gray-50">
            </div>

            @error('image')<p class="mt-2 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

        {{-- Overlay Editor — only shown when an image is saved --}}
        @if(isset($chart) && $chart->image)
        <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900 mb-6">
            <h3 class="mb-1 font-semibold text-gray-800 dark:text-white">
                @lang('size-guide::app.admin.size-guide.overlays-title')
            </h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-5">
                @lang('size-guide::app.admin.size-guide.overlays-hint')
            </p>

            {{-- Field palette --}}
            <div class="mb-4 flex flex-wrap gap-2" id="sg-palette">
                @php
                    $overlayFields = [
                        'p_waist'    => __('size-guide::app.admin.size-guide.ov-p-waist'),
                        'p_length'   => __('size-guide::app.admin.size-guide.ov-p-length'),
                        'p_shoulder' => __('size-guide::app.admin.size-guide.ov-p-shoulder'),
                        'p_chest'    => __('size-guide::app.admin.size-guide.ov-p-chest'),
                        'chest'      => __('size-guide::app.admin.size-guide.ov-chest'),
                        'waist'      => __('size-guide::app.admin.size-guide.ov-waist'),
                        'hips'       => __('size-guide::app.admin.size-guide.ov-hips'),
                        'height'     => __('size-guide::app.admin.size-guide.ov-height'),
                    ];
                    $existingOverlays = $chart->image_overlays ?? [];
                @endphp
                @foreach($overlayFields as $field => $label)
                <button type="button" data-field="{{ $field }}" id="sg-chip-{{ $field }}"
                        class="sg-palette-chip rounded-full border px-3 py-1 text-xs font-semibold transition
                               {{ isset($existingOverlays[$field]) ? 'border-orange-400 bg-orange-50 text-orange-600' : 'border-gray-300 text-gray-600 hover:border-orange-400 hover:text-orange-500' }}">
                    + {{ $label }}
                </button>
                @endforeach
            </div>

            {{-- Canvas --}}
            <div style="position:relative;display:inline-block;max-width:100%;min-width:200px" id="sg-overlay-canvas">
                <img src="{{ asset('storage/' . $chart->image) }}"
                     id="sg-overlay-img"
                     style="display:block;max-width:100%;max-height:600px;border-radius:10px;border:1px solid #e5e7eb;object-fit:contain;user-select:none;pointer-events:none">
            </div>

            <p class="mt-3 text-xs text-gray-400">
                @lang('size-guide::app.admin.size-guide.overlays-drag-hint')
            </p>

            <input type="hidden" name="image_overlays" id="sg-overlay-json"
                   value="{{ json_encode($existingOverlays) }}">
        </div>

        <style>
            .sg-pin {
                position: absolute;
                transform: translate(-50%, -50%);
                cursor: grab;
                z-index: 20;
                user-select: none;
                display: inline-flex;
                align-items: center;
                gap: 4px;
                background: rgba(255,255,255,.95);
                border: 1.5px solid #FF6B35;
                border-radius: 999px;
                padding: 3px 8px 3px 10px;
                font-size: 11px;
                font-weight: 700;
                color: #FF6B35;
                box-shadow: 0 2px 8px rgba(0,0,0,.18);
                white-space: nowrap;
            }
            .sg-pin:active { cursor: grabbing; }
            .sg-pin-x {
                background: none; border: none; color: #FF6B35;
                font-size: 14px; line-height: 1; cursor: pointer;
                padding: 0 2px; margin-left: 2px;
            }
        </style>

        <script>
        (function () {
            var LABELS = @json($overlayFields);
            var state  = {};

            /* always fresh lookup — never cache DOM refs */
            function cv()  { return document.getElementById('sg-overlay-canvas'); }
            function jin() { return document.getElementById('sg-overlay-json'); }

            function save() {
                var j = jin(); if (j) j.value = JSON.stringify(state);
            }

            function markChip(f, active) {
                var c = document.getElementById('sg-chip-' + f);
                if (!c) return;
                c.style.borderColor  = active ? '#FB923C' : '';
                c.style.background   = active ? '#FFF7ED' : '';
                c.style.color        = active ? '#C2410C' : '';
            }

            function addPin(field, x, y) {
                var canvas = cv(); if (!canvas) return;

                /* remove if already exists */
                var old = canvas.querySelector('[data-sgpin="' + field + '"]');
                if (old) canvas.removeChild(old);

                var pin = document.createElement('div');
                pin.setAttribute('data-sgpin', field);
                pin.style.cssText =
                    'position:absolute;left:' + x + '%;top:' + y + '%;' +
                    'transform:translate(-50%,-50%);z-index:20;cursor:grab;' +
                    'user-select:none;display:inline-flex;align-items:center;gap:4px;' +
                    'background:rgba(255,255,255,.95);border:1.5px solid #FF6B35;' +
                    'border-radius:999px;padding:3px 8px 3px 10px;font-size:11px;' +
                    'font-weight:700;color:#FF6B35;box-shadow:0 2px 8px rgba(0,0,0,.18);' +
                    'white-space:nowrap;touch-action:none';

                var lbl = document.createElement('span');
                lbl.textContent = LABELS[field] || field;

                var xBtn = document.createElement('button');
                xBtn.type = 'button';
                xBtn.style.cssText = 'background:none;border:none;color:#FF6B35;font-size:15px;line-height:1;cursor:pointer;padding:0 2px;margin-left:2px;flex-shrink:0';
                xBtn.textContent = '×';
                xBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    var c = cv();
                    if (c && pin.parentNode === c) c.removeChild(pin);
                    delete state[field];
                    save();
                    markChip(field, false);
                });

                pin.appendChild(lbl);
                pin.appendChild(xBtn);
                canvas.appendChild(pin);

                /* drag */
                var dragging = false, sX, sY, sL, sT;
                pin.addEventListener('pointerdown', function (e) {
                    if (e.target === xBtn) return;
                    e.preventDefault();
                    pin.setPointerCapture(e.pointerId);
                    dragging = true; sX = e.clientX; sY = e.clientY;
                    sL = parseFloat(pin.style.left);
                    sT = parseFloat(pin.style.top);
                    pin.style.cursor = 'grabbing';
                });
                pin.addEventListener('pointermove', function (e) {
                    if (!dragging) return;
                    var r = cv().getBoundingClientRect();
                    var nx = Math.max(2, Math.min(98, sL + (e.clientX - sX) / r.width  * 100));
                    var ny = Math.max(2, Math.min(98, sT + (e.clientY - sY) / r.height * 100));
                    pin.style.left = nx + '%';
                    pin.style.top  = ny + '%';
                    state[field] = { x: Math.round(nx * 10) / 10, y: Math.round(ny * 10) / 10 };
                    save();
                });
                pin.addEventListener('pointerup', function () {
                    dragging = false; pin.style.cursor = 'grab';
                });
            }

            function init() {
                var j = jin(); if (!j) return;
                try { var p = JSON.parse(j.value); state = (p && !Array.isArray(p)) ? p : {}; } catch(e) { state = {}; }
                Object.keys(state).forEach(function (f) {
                    var p = state[f];
                    if (p) { addPin(f, p.x, p.y); markChip(f, true); }
                });
            }

            /* chip clicks — event delegation, survives Vue mount */
            document.addEventListener('click', function (e) {
                var chip = e.target && e.target.closest && e.target.closest('.sg-palette-chip');
                if (!chip) return;
                var f = chip.dataset.field;
                if (state[f]) {
                    /* toggle off — remove pin */
                    var c = cv();
                    if (c) { var p = c.querySelector('[data-sgpin="' + f + '"]'); if (p) c.removeChild(p); }
                    delete state[f]; save(); markChip(f, false);
                } else {
                    /* add pin at center */
                    state[f] = { x: 50, y: 50 }; save();
                    addPin(f, 50, 50); markChip(f, true);
                }
            });

            /* init after Vue finishes mounting (Vue also uses load event) */
            window.addEventListener('load', function () { setTimeout(init, 200); });
        })();
        </script>
        @endif

        {{-- Size Rows --}}
        <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-800 dark:text-white">@lang('size-guide::app.admin.size-guide.sizes')</h3>
                <div class="flex items-center gap-2">
                    {{-- CSV Upload --}}
                    <label class="cursor-pointer rounded border border-blue-600 px-3 py-1 text-xs font-semibold text-blue-600 hover:bg-blue-50 dark:hover:bg-gray-800" title="Upload CSV">
                        ↑ Upload CSV
                        <input type="file" id="sg-csv-input" accept=".csv,text/csv" class="hidden">
                    </label>
                    <a href="data:text/csv;charset=utf-8,Label,EU,UK,US,Chest Min,Chest Max,Waist Min,Waist Max,Hips Min,Hips Max,Height Min,Height Max,Product Chest,Product Waist,Product Length,Product Shoulder%0AS,44,8,S,86,91,66,71,91,96,155,165,,,,%0AM,46,10,M,91,96,71,76,96,101,160,170,,,,"
                       download="size-guide-template.csv"
                       class="rounded border border-gray-300 px-3 py-1 text-xs font-semibold text-gray-600 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-800">
                        ↓ Template
                    </a>
                    <button type="button" id="add-row"
                            class="rounded bg-blue-600 px-3 py-1 text-xs font-semibold text-white hover:bg-blue-700">
                        + @lang('size-guide::app.admin.size-guide.add-size')
                    </button>
                </div>
            </div>

            @php
                $ch   = $chart->column_headers ?? [];
                $hInp = 'w-full rounded border border-transparent px-1 py-0.5 text-xs font-semibold text-gray-500 dark:text-gray-400 bg-transparent hover:border-gray-300 focus:border-blue-400 focus:bg-white dark:focus:bg-gray-700 focus:outline-none dark:hover:border-gray-600 cursor-text text-center';
            @endphp
            <div class="overflow-x-auto">
                <table class="w-full text-xs" id="rows-table">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr class="text-left text-gray-500" title="Click any header to rename it">
                            <th class="px-2 py-2 min-w-[70px]">
                                <input name="column_headers[label]" value="{{ $ch['label'] ?? 'Label' }}" class="{{ $hInp }}" title="Edit column name">
                                <span class="block text-center text-gray-400 text-[9px] font-normal">*required</span>
                            </th>
                            <th class="px-2 py-2 min-w-[50px]">
                                <input name="column_headers[eu]" value="{{ $ch['eu'] ?? 'EU' }}" class="{{ $hInp }}" title="Edit column name">
                            </th>
                            <th class="px-2 py-2 min-w-[50px]">
                                <input name="column_headers[uk]" value="{{ $ch['uk'] ?? 'UK' }}" class="{{ $hInp }}" title="Edit column name">
                            </th>
                            <th class="px-2 py-2 min-w-[50px]">
                                <input name="column_headers[us]" value="{{ $ch['us'] ?? 'US' }}" class="{{ $hInp }}" title="Edit column name">
                            </th>
                            <th class="px-2 py-2 min-w-[110px]" colspan="2">
                                <input name="column_headers[chest]" value="{{ $ch['chest'] ?? 'Chest' }}" class="{{ $hInp }}" title="Edit column name">
                                <span class="block text-center text-gray-400 text-[9px] font-normal">(min / max)</span>
                            </th>
                            <th class="px-2 py-2 min-w-[110px]" colspan="2">
                                <input name="column_headers[waist]" value="{{ $ch['waist'] ?? 'Waist' }}" class="{{ $hInp }}" title="Edit column name">
                                <span class="block text-center text-gray-400 text-[9px] font-normal">(min / max)</span>
                            </th>
                            <th class="px-2 py-2 min-w-[110px]" colspan="2">
                                <input name="column_headers[hips]" value="{{ $ch['hips'] ?? 'Hips' }}" class="{{ $hInp }}" title="Edit column name">
                                <span class="block text-center text-gray-400 text-[9px] font-normal">(min / max)</span>
                            </th>
                            <th class="px-2 py-2 min-w-[110px]" colspan="2">
                                <input name="column_headers[height]" value="{{ $ch['height'] ?? 'Height' }}" class="{{ $hInp }}" title="Edit column name">
                                <span class="block text-center text-gray-400 text-[9px] font-normal">(min / max)</span>
                            </th>
                            <th class="px-2 py-2 min-w-[60px]">
                                <input name="column_headers[product_chest]" value="{{ $ch['product_chest'] ?? 'P.Chest' }}" class="{{ $hInp }}" title="Edit column name">
                            </th>
                            <th class="px-2 py-2 min-w-[60px]">
                                <input name="column_headers[product_waist]" value="{{ $ch['product_waist'] ?? 'P.Waist' }}" class="{{ $hInp }}" title="Edit column name">
                            </th>
                            <th class="px-2 py-2 min-w-[60px]">
                                <input name="column_headers[product_length]" value="{{ $ch['product_length'] ?? 'P.Length' }}" class="{{ $hInp }}" title="Edit column name">
                            </th>
                            <th class="px-2 py-2 min-w-[70px]">
                                <input name="column_headers[product_shoulder]" value="{{ $ch['product_shoulder'] ?? 'P.Shoulder' }}" class="{{ $hInp }}" title="Edit column name">
                            </th>
                            <th class="px-2 py-2"></th>
                        </tr>
                    </thead>
                    <tbody id="rows-body">
                        @php $rows = old('rows', isset($chart) ? $chart->rows->toArray() : []); @endphp
                        @foreach($rows as $i => $row)
                            @include('size-guide::admin.size-guide.partials.row', ['i' => $i, 'row' => $row])
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p class="mt-2 text-xs text-gray-400">P. = Product measurement (actual garment dimensions) &nbsp;·&nbsp; Click any column header to rename it</p>
        </div>

        {{-- Product Assignment --}}
        <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900 mb-6">
            <h3 class="mb-1 font-semibold text-gray-800 dark:text-white">@lang('size-guide::app.admin.size-guide.assign-products')</h3>
            <p class="mb-4 text-xs text-gray-400">@lang('size-guide::app.admin.size-guide.assign-products-hint')</p>

            {{-- Search box --}}
            <div class="relative mb-3">
                <input type="text" id="sg-prod-search"
                       placeholder="@lang('size-guide::app.admin.size-guide.search-products')"
                       autocomplete="off"
                       class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-blue-500 focus:outline-none">
                <ul id="sg-prod-results"
                    class="absolute z-50 mt-1 hidden w-full rounded-md border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-900 max-h-56 overflow-y-auto text-sm">
                </ul>
            </div>

            {{-- Selected chips --}}
            <div id="sg-prod-chips" class="flex flex-wrap gap-2 min-h-[36px]">
                @php $assignedProducts = $assignedProducts ?? []; @endphp
                @foreach($assignedProducts as $ap)
                    <span class="sg-chip flex items-center gap-1.5 rounded-full bg-blue-50 border border-blue-200 px-3 py-1 text-xs font-medium text-blue-700 dark:bg-blue-900/20 dark:border-blue-800 dark:text-blue-300"
                          data-id="{{ $ap['id'] }}">
                        {{ $ap['name'] ?: $ap['sku'] }}
                        <button type="button" class="sg-chip-remove text-blue-400 hover:text-red-500 leading-none font-bold">×</button>
                        <input type="hidden" name="products[]" value="{{ $ap['id'] }}">
                    </span>
                @endforeach
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit"
                    class="rounded-md bg-blue-600 px-6 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                @lang('size-guide::app.admin.size-guide.save')
            </button>
            <a href="{{ route('admin.size-guide.index') }}"
               class="rounded-md border border-gray-300 px-6 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800">
                @lang('size-guide::app.admin.size-guide.cancel')
            </a>
        </div>
    </form>

    @push('scripts')
    <script>
    (function() {
        var rowIndex = {{ count($rows ?? []) }};
        var inp = 'w-full rounded border border-gray-300 px-1 py-1 dark:border-gray-600 dark:bg-gray-800 dark:text-white text-xs focus:outline-none focus:border-blue-400';

        function rowHtml(i, vals) {
            vals = vals || {};
            var n = 'rows['+i+']';
            var html = '<tr class="border-t border-gray-100 dark:border-gray-800">';
            html += '<td class="px-1 py-1"><input name="'+n+'[label]" class="'+inp+'" placeholder="S,M,L..." value="'+(vals.label||'')+'" required></td>';
            ['eu_size','uk_size','us_size'].forEach(function(f) {
                html += '<td class="px-1 py-1"><input name="'+n+'['+f+']" class="'+inp+'" placeholder="—" value="'+(vals[f]||'')+'"></td>';
            });
            ['chest','waist','hips','height'].forEach(function(f) {
                html += '<td class="px-1 py-1"><input name="'+n+'['+f+'_min]" type="number" step="0.1" class="'+inp+'" placeholder="min" value="'+(vals[f+'_min']||'')+'"></td>';
                html += '<td class="px-1 py-1"><input name="'+n+'['+f+'_max]" type="number" step="0.1" class="'+inp+'" placeholder="max" value="'+(vals[f+'_max']||'')+'"></td>';
            });
            ['product_chest','product_waist','product_length','product_shoulder'].forEach(function(f) {
                html += '<td class="px-1 py-1"><input name="'+n+'['+f+']" type="number" step="0.1" class="'+inp+'" placeholder="cm" value="'+(vals[f]||'')+'"></td>';
            });
            html += '<td class="px-1 py-1"><button type="button" class="remove-row text-red-400 hover:text-red-600 text-lg leading-none">×</button></td>';
            html += '</tr>';
            return html;
        }

        function addRow(vals) {
            var tbody = document.getElementById('rows-body');
            if (!tbody) return;
            var tmp = document.createElement('tbody');
            tmp.innerHTML = rowHtml(rowIndex++, vals);
            tbody.appendChild(tmp.firstElementChild);
        }

        // Event delegation — survives Vue mount
        document.addEventListener('click', function(e) {
            if (e.target.id === 'add-row' || e.target.closest('#add-row')) {
                addRow();
            }
            if (e.target.classList.contains('remove-row')) {
                e.target.closest('tr').remove();
            }
            if (e.target.classList.contains('sg-chip-remove')) {
                var chip = e.target.closest('.sg-chip');
                if (chip) {
                    delete selectedIds[chip.dataset.id];
                    chip.remove();
                }
            }
        });

        // ── Product search ────────────────────────────────────────────────
        var searchUrl  = '{{ route('admin.size-guide.products.search') }}';
        var selectedIds = {};

        // Pre-populate from server-rendered chips
        document.querySelectorAll('.sg-chip').forEach(function(chip) {
            selectedIds[chip.dataset.id] = true;
        });

        var searchTimer = null;
        document.addEventListener('input', function(e) {
            if (e.target.id !== 'sg-prod-search') return;
            clearTimeout(searchTimer);
            var q = e.target.value.trim();
            searchTimer = setTimeout(function() { doSearch(q); }, 250);
        });

        function doSearch(q) {
            var list = document.getElementById('sg-prod-results');
            if (!list) return;
            fetch(searchUrl + '?q=' + encodeURIComponent(q), { headers: { 'Accept': 'application/json' } })
                .then(function(r) { return r.json(); })
                .then(function(items) {
                    list.innerHTML = '';
                    if (!items.length) {
                        list.innerHTML = '<li class="px-3 py-2 text-gray-400 text-xs">No products found</li>';
                        list.classList.remove('hidden');
                        return;
                    }
                    items.forEach(function(p) {
                        if (selectedIds[p.id]) return;
                        var li = document.createElement('li');
                        li.className = 'flex items-center justify-between px-3 py-2 hover:bg-blue-50 dark:hover:bg-gray-800 cursor-pointer';
                        li.innerHTML = '<span class="font-medium text-gray-800 dark:text-white">' + (p.name || p.sku) + '</span>'
                            + '<span class="text-xs text-gray-400 ml-2">' + p.sku + '</span>';
                        li.addEventListener('mousedown', function(ev) {
                            ev.preventDefault();
                            addChip(p);
                            list.classList.add('hidden');
                            document.getElementById('sg-prod-search').value = '';
                        });
                        list.appendChild(li);
                    });
                    list.classList.remove('hidden');
                });
        }

        function addChip(p) {
            if (selectedIds[p.id]) return;
            selectedIds[p.id] = true;
            var chips = document.getElementById('sg-prod-chips');
            var span = document.createElement('span');
            span.className = 'sg-chip flex items-center gap-1.5 rounded-full bg-blue-50 border border-blue-200 px-3 py-1 text-xs font-medium text-blue-700 dark:bg-blue-900/20 dark:border-blue-800 dark:text-blue-300';
            span.dataset.id = p.id;
            span.innerHTML = (p.name || p.sku)
                + '<button type="button" class="sg-chip-remove text-blue-400 hover:text-red-500 leading-none font-bold ml-1">×</button>'
                + '<input type="hidden" name="products[]" value="' + p.id + '">';
            chips.appendChild(span);
        }

        // Close dropdown on outside click
        document.addEventListener('click', function(e) {
            var list = document.getElementById('sg-prod-results');
            if (list && !list.contains(e.target) && e.target.id !== 'sg-prod-search') {
                list.classList.add('hidden');
            }
        });

        // Focus: show dropdown again
        document.addEventListener('focus', function(e) {
            if (e.target.id !== 'sg-prod-search') return;
            var q = e.target.value.trim();
            if (q) doSearch(q);
        }, true);

        // ── CSV Upload
        document.addEventListener('change', function(e) {
            if (e.target.id !== 'sg-csv-input') return;
            var file = e.target.files[0];
            if (!file) return;
            var reader = new FileReader();
            reader.onload = function(ev) {
                var lines = ev.target.result.split(/\r?\n/).filter(function(l) { return l.trim(); });
                // skip header row
                for (var i = 1; i < lines.length; i++) {
                    var cols = lines[i].split(',');
                    if (!cols[0]) continue;
                    addRow({
                        label:           cols[0]  || '',
                        eu_size:         cols[1]  || '',
                        uk_size:         cols[2]  || '',
                        us_size:         cols[3]  || '',
                        chest_min:       cols[4]  || '',
                        chest_max:       cols[5]  || '',
                        waist_min:       cols[6]  || '',
                        waist_max:       cols[7]  || '',
                        hips_min:        cols[8]  || '',
                        hips_max:        cols[9]  || '',
                        height_min:      cols[10] || '',
                        height_max:      cols[11] || '',
                        product_chest:   cols[12] || '',
                        product_waist:   cols[13] || '',
                        product_length:  cols[14] || '',
                        product_shoulder:cols[15] || '',
                    });
                }
                e.target.value = '';
            };
            reader.readAsText(file);
        });
    })();
    </script>
    @endpush
</x-admin::layouts>
