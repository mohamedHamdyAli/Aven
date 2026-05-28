<?php
    $rows   = $chart->rows;
    $gender = $chart->gender ?? 'mens';
    $ch     = $chart->column_headers ?? [];
    $hdr    = [
        'label'           => $ch['label']           ?? __('size-guide::app.shop.size-guide.label'),
        'chest'           => $ch['chest']            ?? __('size-guide::app.shop.size-guide.chest'),
        'waist'           => $ch['waist']            ?? __('size-guide::app.shop.size-guide.waist'),
        'hips'            => $ch['hips']             ?? __('size-guide::app.shop.size-guide.hips'),
        'height'          => $ch['height']           ?? __('size-guide::app.shop.size-guide.height'),
        'product_chest'   => $ch['product_chest']    ?? 'Chest',
        'product_waist'   => $ch['product_waist']    ?? 'Waist',
        'product_length'  => $ch['product_length']   ?? 'Length',
        'product_shoulder'=> $ch['product_shoulder'] ?? 'Shoulder',
    ];
?>

<?php if (isset($component)) { $__componentOriginal2643b7d197f48caff2f606750db81304 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2643b7d197f48caff2f606750db81304 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.layouts.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::layouts'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('title', null, []); ?> 
        <?php echo app('translator')->get('size-guide::app.shop.size-guide.title'); ?> — <?php echo e($product->name); ?>

     <?php $__env->endSlot(); ?>

    <style>
        .sg-card { border-radius:14px; border:1px solid #f0f0f0; background:#fff; box-shadow:0 1px 6px rgba(0,0,0,.06); }
        .sg-pill { border-radius:999px; border:1.5px solid #d1d5db; padding:5px 16px; font-size:12px; font-weight:600; background:transparent; color:#374151; cursor:pointer; transition:all .15s; white-space:nowrap; }
        .sg-pill:hover { border-color:#FF6B35; color:#FF6B35; }
        .sg-mrow { display:flex; align-items:center; justify-content:space-between; padding:10px 0; border-bottom:1px solid #f5f5f5; }
        .sg-mrow:last-child { border-bottom:none; }
    </style>

    <div style="max-width:980px;margin:0 auto;padding:20px 16px 48px">

        
        <nav style="display:flex;align-items:center;gap:6px;font-size:11px;color:#9ca3af;margin-bottom:16px;flex-wrap:wrap">
            <a href="<?php echo e(route('shop.home.index')); ?>" style="color:inherit;text-decoration:none">Home</a>
            <svg width="8" height="8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            <a href="<?php echo e(route('shop.product_or_category.index', $product->url_key)); ?>"
               style="color:inherit;text-decoration:none;max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?php echo e($product->name); ?></a>
            <svg width="8" height="8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            <span style="color:#374151"><?php echo app('translator')->get('size-guide::app.shop.size-guide.title'); ?></span>
        </nav>

        
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:18px">
            <div style="width:36px;height:36px;border-radius:10px;background:#fff3ed;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#FF6B35" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 20h20M5 4h14a1 1 0 0 1 1 1v11H4V5a1 1 0 0 1 1-1z"/>
                    <line x1="9" y1="8" x2="15" y2="8"/><line x1="9" y1="12" x2="15" y2="12"/>
                </svg>
            </div>
            <div>
                <h1 style="font-size:16px;font-weight:700;color:#111827;margin:0"><?php echo app('translator')->get('size-guide::app.shop.size-guide.title'); ?></h1>
                <p style="font-size:11px;color:#6b7280;margin:2px 0 0"><?php echo e($product->name); ?></p>
            </div>
        </div>

        
        <div style="display:flex;flex-wrap:wrap;gap:12px;align-items:start;margin-bottom:12px">

            
            <?php if($chart->image): ?>
            <div class="sg-card" style="overflow:hidden;flex:1;min-width:280px">
                <div style="position:relative;display:flex;align-items:center;justify-content:center;background:#fafafa;min-height:200px">
                    <div style="position:relative;display:inline-block">
                        <img src="<?php echo e(asset('storage/' . $chart->image)); ?>"
                             alt="<?php echo app('translator')->get('size-guide::app.shop.size-guide.title'); ?>"
                             style="max-width:100%;max-height:440px;display:block;object-fit:contain;user-select:none">

                        <?php $overlays = $chart->image_overlays ?? []; ?>
                        <?php $__currentLoopData = $overlays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $pos): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($pos && isset($pos['x']) && isset($pos['y'])): ?>
                        <div class="sg-ov-pin" data-field="<?php echo e($field); ?>"
                             style="position:absolute;left:<?php echo e($pos['x']); ?>%;top:<?php echo e($pos['y']); ?>%;transform:translate(-50%,-50%);pointer-events:none;white-space:nowrap;z-index:10">
                            <div style="display:inline-flex;align-items:center;gap:3px;background:rgba(255,255,255,.96);border:1.5px solid #FF6B35;border-radius:999px;padding:3px 9px;font-size:11px;font-weight:700;color:#FF6B35;box-shadow:0 2px 8px rgba(0,0,0,.16)">
                                <span class="sg-ov-val" data-field="<?php echo e($field); ?>" style="font-size:12px;font-weight:800">—</span>
                                <span class="sg-unit-lbl" style="font-size:9px;font-weight:600;color:#9ca3af">CM</span>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            
            <div style="display:flex;flex-direction:column;gap:10px;flex:1;min-width:260px">

                
                <div class="sg-card" style="padding:14px 16px">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px">
                        <span style="font-size:10px;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:#9ca3af">
                            <?php echo app('translator')->get('size-guide::app.shop.size-guide.label'); ?>
                        </span>
                        <div style="display:flex;overflow:hidden;border-radius:999px;border:1.5px solid #e5e7eb">
                            <button data-sg-unit="in" style="padding:3px 10px;font-size:10px;font-weight:700;color:#6b7280;border:none;background:transparent;cursor:pointer">
                                <?php echo app('translator')->get('size-guide::app.shop.size-guide.in'); ?>
                            </button>
                            <button data-sg-unit="cm" style="padding:3px 10px;font-size:10px;font-weight:700;color:#fff;background:#FF6B35;border:none;cursor:pointer">
                                <?php echo app('translator')->get('size-guide::app.shop.size-guide.cm'); ?>
                            </button>
                        </div>
                    </div>
                    <div style="display:flex;flex-wrap:wrap;gap:7px">
                        <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <button type="button" data-sg-size="<?php echo e($i); ?>" class="sg-pill"
                                style="<?php echo e($i === 0 ? 'background:#FF6B35;color:#fff;border-color:#FF6B35' : ''); ?>">
                            <?php echo e($row->label); ?>

                        </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                
                <div class="sg-card" style="padding:14px 16px">
                    
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
                        <span id="sg-dyn-label" style="font-size:15px;font-weight:800;color:#FF6B35">
                            <?php echo e($rows->first()?->label ?? ''); ?>

                        </span>
                        <div style="display:flex;overflow:hidden;border-radius:8px;border:1.5px solid #e5e7eb">
                            <button data-sg-stab="body"
                                    style="padding:4px 10px;font-size:10px;font-weight:700;color:#fff;background:#FF6B35;border:none;cursor:pointer">
                                <?php echo app('translator')->get('size-guide::app.shop.size-guide.body-charts'); ?>
                            </button>
                            <button data-sg-stab="product"
                                    style="padding:4px 10px;font-size:10px;font-weight:500;color:#9ca3af;background:transparent;border:none;cursor:pointer">
                                <?php echo app('translator')->get('size-guide::app.shop.size-guide.product-chart'); ?>
                            </button>
                        </div>
                    </div>

                    
                    <div id="sg-dyn-body">
                        <?php $__currentLoopData = [
                            ['chest',  'sg-body-chest',  $hdr['chest']],
                            ['waist',  'sg-body-waist',  $hdr['waist']],
                            ['hips',   'sg-body-hips',   $hdr['hips']],
                            ['height', 'sg-body-height', $hdr['height']],
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$key, $cls, $lbl]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="sg-mrow">
                            <span style="font-size:12px;font-weight:600;color:#6b7280"><?php echo e($lbl); ?></span>
                            <div style="display:flex;align-items:baseline;gap:3px">
                                <span class="sg-dyn-val" data-key="<?php echo e($cls); ?>" style="font-size:14px;font-weight:800;color:#111827">—</span>
                                <span class="sg-unit-lbl" style="font-size:10px;font-weight:600;color:#9ca3af">CM</span>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    
                    <div id="sg-dyn-product" style="display:none">
                        <?php $__currentLoopData = [
                            [$hdr['product_chest'],    'sg-prod-chest'],
                            [$hdr['product_waist'],    'sg-prod-waist'],
                            [$hdr['product_length'],   'sg-prod-length'],
                            [$hdr['product_shoulder'], 'sg-prod-shoulder'],
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$lbl,$cls]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="sg-mrow">
                            <span style="font-size:12px;font-weight:600;color:#6b7280"><?php echo e($lbl); ?></span>
                            <div style="display:flex;align-items:baseline;gap:3px">
                                <span class="sg-dyn-val" data-key="<?php echo e($cls); ?>" style="font-size:14px;font-weight:800;color:#111827">—</span>
                                <span class="sg-unit-lbl" style="font-size:10px;font-weight:600;color:#9ca3af">CM</span>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <p style="font-size:10px;color:#d1d5db;margin:12px 0 0">
                        <?php echo app('translator')->get('size-guide::app.shop.size-guide.disclaimer'); ?>
                    </p>
                </div>

            </div>
        </div>

        
        <div class="sg-card" style="margin-bottom:12px">
            <div style="display:flex;border-bottom:1px solid #f5f5f5">
                <button data-sg-stab="body"
                        style="flex:1;padding:11px;font-size:11px;font-weight:700;color:#FF6B35;border:none;border-bottom:2px solid #FF6B35;background:none;cursor:pointer">
                    <?php echo app('translator')->get('size-guide::app.shop.size-guide.body-charts'); ?>
                </button>
                <button data-sg-stab="product"
                        style="flex:1;padding:11px;font-size:11px;font-weight:500;color:#9ca3af;border:none;border-bottom:2px solid transparent;background:none;cursor:pointer">
                    <?php echo app('translator')->get('size-guide::app.shop.size-guide.product-chart'); ?>
                </button>
            </div>

            <div id="sg-sub-body" style="overflow-x:auto">
                <table style="width:100%;border-collapse:collapse;font-size:11px">
                    <thead>
                        <tr style="background:#fff8f5">
                            <th style="padding:9px 14px;text-align:left;font-weight:700;color:#374151"><?php echo e($hdr['label']); ?></th>
                            <th style="padding:9px 6px;text-align:center;font-weight:600;color:#6b7280"><?php echo e($hdr['chest']); ?><br><span class="sg-unit-lbl" style="font-weight:400;color:#9ca3af;font-size:10px">CM</span></th>
                            <th style="padding:9px 6px;text-align:center;font-weight:600;color:#6b7280"><?php echo e($hdr['waist']); ?><br><span class="sg-unit-lbl" style="font-weight:400;color:#9ca3af;font-size:10px">CM</span></th>
                            <th style="padding:9px 6px;text-align:center;font-weight:600;color:#6b7280"><?php echo e($hdr['hips']); ?><br><span class="sg-unit-lbl" style="font-weight:400;color:#9ca3af;font-size:10px">CM</span></th>
                            <th style="padding:9px 14px;text-align:center;font-weight:600;color:#6b7280"><?php echo e($hdr['height']); ?><br><span class="sg-unit-lbl" style="font-weight:400;color:#9ca3af;font-size:10px">CM</span></th>
                        </tr>
                    </thead>
                    <tbody id="sg-body-table-body">
                        <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="sg-row" data-row="<?php echo e($i); ?>" style="border-top:1px solid #f8f8f8<?php echo e($i === 0 ? ';background:#fff8f5' : ''); ?>">
                            <td style="padding:9px 14px;font-weight:700;color:#111827"><?php echo e($row->label); ?></td>
                            <td class="sg-body-chest"  data-min="<?php echo e($row->chest_min); ?>" data-max="<?php echo e($row->chest_max); ?>" style="padding:9px 6px;text-align:center;color:#374151"><?php echo e($row->chest_min  ? ($row->chest_max && $row->chest_max != $row->chest_min ? $row->chest_min.'–'.$row->chest_max : $row->chest_min) : '—'); ?></td>
                            <td class="sg-body-waist"  data-min="<?php echo e($row->waist_min); ?>" data-max="<?php echo e($row->waist_max); ?>" style="padding:9px 6px;text-align:center;color:#374151"><?php echo e($row->waist_min  ? ($row->waist_max && $row->waist_max != $row->waist_min ? $row->waist_min.'–'.$row->waist_max : $row->waist_min) : '—'); ?></td>
                            <td class="sg-body-hips"   data-min="<?php echo e($row->hips_min); ?>" data-max="<?php echo e($row->hips_max); ?>" style="padding:9px 6px;text-align:center;color:#374151"><?php echo e($row->hips_min   ? ($row->hips_max  && $row->hips_max  != $row->hips_min  ? $row->hips_min.'–'.$row->hips_max   : $row->hips_min ) : '—'); ?></td>
                            <td class="sg-body-height" data-min="<?php echo e($row->height_min); ?>" data-max="<?php echo e($row->height_max); ?>" style="padding:9px 14px;text-align:center;color:#374151"><?php echo e($row->height_min ? ($row->height_max && $row->height_max != $row->height_min ? $row->height_min.'–'.$row->height_max : $row->height_min) : '—'); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <div id="sg-sub-product" style="display:none;overflow-x:auto">
                <table style="width:100%;border-collapse:collapse;font-size:11px">
                    <thead>
                        <tr style="background:#fff8f5">
                            <th style="padding:9px 14px;text-align:left;font-weight:700;color:#374151"><?php echo e($hdr['label']); ?></th>
                            <th style="padding:9px 6px;text-align:center;font-weight:600;color:#6b7280"><?php echo e($hdr['product_chest']); ?><br><span class="sg-unit-lbl" style="font-weight:400;color:#9ca3af;font-size:10px">CM</span></th>
                            <th style="padding:9px 6px;text-align:center;font-weight:600;color:#6b7280"><?php echo e($hdr['product_waist']); ?><br><span class="sg-unit-lbl" style="font-weight:400;color:#9ca3af;font-size:10px">CM</span></th>
                            <th style="padding:9px 6px;text-align:center;font-weight:600;color:#6b7280"><?php echo e($hdr['product_length']); ?><br><span class="sg-unit-lbl" style="font-weight:400;color:#9ca3af;font-size:10px">CM</span></th>
                            <th style="padding:9px 14px;text-align:center;font-weight:600;color:#6b7280"><?php echo e($hdr['product_shoulder']); ?><br><span class="sg-unit-lbl" style="font-weight:400;color:#9ca3af;font-size:10px">CM</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="sg-row" data-row="<?php echo e($i); ?>" style="border-top:1px solid #f8f8f8<?php echo e($i === 0 ? ';background:#fff8f5' : ''); ?>">
                            <td style="padding:9px 14px;font-weight:700;color:#111827"><?php echo e($row->label); ?></td>
                            <td class="sg-prod-chest"    data-val="<?php echo e($row->product_chest); ?>" style="padding:9px 6px;text-align:center;color:#374151"><?php echo e($row->product_chest    ?? '—'); ?></td>
                            <td class="sg-prod-waist"    data-val="<?php echo e($row->product_waist); ?>" style="padding:9px 6px;text-align:center;color:#374151"><?php echo e($row->product_waist    ?? '—'); ?></td>
                            <td class="sg-prod-length"   data-val="<?php echo e($row->product_length); ?>" style="padding:9px 6px;text-align:center;color:#374151"><?php echo e($row->product_length   ?? '—'); ?></td>
                            <td class="sg-prod-shoulder" data-val="<?php echo e($row->product_shoulder); ?>" style="padding:9px 14px;text-align:center;color:#374151"><?php echo e($row->product_shoulder ?? '—'); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>

        
        <div class="sg-card" style="padding:16px">
            <h2 style="font-size:13px;font-weight:700;color:#111827;margin:0 0 12px">
                <?php echo app('translator')->get('size-guide::app.shop.size-guide.tab-recommend'); ?>
            </h2>
            <div style="border-radius:10px;background:#fff8f5;border:1px solid #ffe4d6;padding:10px 12px;margin-bottom:12px">
                <?php $__currentLoopData = [['chest','chest-hint'],['waist','waist-hint'],['hips','hips-hint']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$lk,$hk]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div style="display:flex;align-items:flex-start;gap:7px;font-size:11px;color:#6b7280<?php echo e(!$loop->first ? ';margin-top:6px' : ''); ?>">
                    <span style="width:5px;height:5px;border-radius:50%;background:#FF6B35;flex-shrink:0;margin-top:3px"></span>
                    <span><strong><?php echo app('translator')->get('size-guide::app.shop.size-guide.' . $lk); ?>:</strong>
                        <?php echo app('translator')->get('size-guide::app.shop.size-guide.' . $hk); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:12px">
                <?php $__currentLoopData = ['chest','waist','hips']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div>
                    <label style="display:block;font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#6b7280;margin-bottom:4px">
                        <?php echo app('translator')->get('size-guide::app.shop.size-guide.' . $mf); ?>
                    </label>
                    <div style="display:flex;overflow:hidden;border-radius:9px;border:1.5px solid #e5e7eb">
                        <input type="number" step="0.5" min="30" max="200" id="sg-inp-<?php echo e($mf); ?>"
                               style="width:100%;padding:7px 8px;font-size:12px;color:#111;border:none;outline:none;background:transparent" placeholder="0">
                        <span class="sg-unit-lbl" style="flex-shrink:0;display:flex;align-items:center;background:#f9fafb;padding:0 6px;font-size:9px;font-weight:700;color:#9ca3af">CM</span>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <button id="sg-rec-btn" type="button"
                    style="width:100%;border-radius:10px;padding:11px;font-size:13px;font-weight:700;color:#fff;background:#FF6B35;border:none;cursor:pointer">
                <?php echo app('translator')->get('size-guide::app.shop.size-guide.get-size'); ?>
            </button>
            <div id="sg-rec-result" style="display:none;margin-top:12px;border-radius:10px;background:#fff8f5;border:1px solid #ffe4d6;padding:16px;text-align:center">
                <p style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;margin:0 0 2px"><?php echo app('translator')->get('size-guide::app.shop.size-guide.result-label'); ?></p>
                <p id="sg-rec-size" style="font-size:48px;font-weight:900;color:#FF6B35;margin:0;line-height:1.1"></p>
                <p id="sg-rec-eu"   style="font-size:12px;color:#6b7280;margin:3px 0 0"></p>
            </div>
            <div id="sg-rec-no-result" style="display:none;margin-top:12px;border-radius:10px;background:#f9fafb;padding:12px;text-align:center;font-size:12px;color:#9ca3af">
                <?php echo app('translator')->get('size-guide::app.shop.size-guide.no-result'); ?>
            </div>
        </div>

    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
    (function () {
        var PID    = <?php echo e($product->id); ?>;
        var unit   = 'cm';
        var selIdx = 0;
        var dynTab = 'body';

        function $id(id) { return document.getElementById(id); }
        function toIn(v) { return v ? Math.round(v / 2.54 * 10) / 10 : null; }
        function conv(v) { return (v && unit === 'in') ? toIn(v) : v; }
        function fmtR(mn, mx) {
            if (!mn && !mx) return '—';
            var a = conv(mn), b = conv(mx);
            return (!b || a == b) ? String(a) : a + '–' + b;
        }

        /* ── clicks ── */
        document.addEventListener('click', function (e) {
            var u = e.target.closest && e.target.closest('[data-sg-unit]');
            if (u) {
                unit = u.dataset.sgUnit;
                document.querySelectorAll('[data-sg-unit]').forEach(function (b) {
                    var on = b.dataset.sgUnit === unit;
                    b.style.background = on ? '#FF6B35' : 'transparent';
                    b.style.color      = on ? '#fff' : '#6b7280';
                });
                document.querySelectorAll('.sg-unit-lbl').forEach(function (l) { l.textContent = unit.toUpperCase(); });
                refreshTables(); refreshDynCard(); refreshImageBadges();
                return;
            }
            var st = e.target.closest && e.target.closest('[data-sg-stab]');
            if (st) { switchSubTab(st.dataset.sgStab); return; }
            var p = e.target.closest && e.target.closest('[data-sg-size]');
            if (p) { selectSize(parseInt(p.dataset.sgSize, 10)); return; }
            if (e.target.id === 'sg-rec-btn') { doRecommend(); return; }
        });

        /* ── sub-tab ── */
        function switchSubTab(name) {
            dynTab = name;

            /* full table */
            ['body','product'].forEach(function (n) {
                var pane = $id('sg-sub-' + n);
                if (pane) pane.style.display = (n === name) ? 'block' : 'none';
            });

            /* full table header buttons */
            document.querySelectorAll('#sg-full-table [data-sg-stab]').forEach(function (b) {
                var on = b.dataset.sgStab === name;
                b.style.color        = on ? '#FF6B35' : '#9ca3af';
                b.style.fontWeight   = on ? '700' : '500';
                b.style.borderBottom = on ? '2px solid #FF6B35' : '2px solid transparent';
            });

            /* dynamic card panels */
            var db = $id('sg-dyn-body'), dp = $id('sg-dyn-product');
            if (db) db.style.display = (name === 'body')    ? 'block' : 'none';
            if (dp) dp.style.display = (name === 'product') ? 'block' : 'none';

            /* dynamic card tab buttons */
            document.querySelectorAll('[data-sg-stab]').forEach(function (b) {
                if (b.closest('#sg-full-table')) return; // handled above
                var on = b.dataset.sgStab === name;
                b.style.background = on ? '#FF6B35' : 'transparent';
                b.style.color      = on ? '#fff'    : '#9ca3af';
                b.style.fontWeight = on ? '700'     : '500';
            });

            refreshDynCard();
        }

        /* ── size select ── */
        function selectSize(idx) {
            selIdx = idx;
            document.querySelectorAll('[data-sg-size]').forEach(function (p, i) {
                var on = (i === idx);
                p.style.background  = on ? '#FF6B35' : 'transparent';
                p.style.color       = on ? '#fff'    : '#374151';
                p.style.borderColor = on ? '#FF6B35' : '#d1d5db';
            });
            document.querySelectorAll('.sg-row').forEach(function (tr) {
                tr.style.background = (parseInt(tr.dataset.row, 10) === idx) ? '#fff8f5' : '';
            });
            refreshDynCard();
            refreshImageBadges();
        }

        /* ── dynamic card ── */
        function refreshDynCard() {
            var bodyRow = document.querySelectorAll('#sg-body-table-body .sg-row')[selIdx];
            var prodRow = document.querySelectorAll('#sg-sub-product .sg-row')[selIdx];

            var lbl = $id('sg-dyn-label');
            if (lbl && bodyRow) {
                var c = bodyRow.querySelector('td:first-child');
                if (c) lbl.textContent = c.textContent.trim();
            }

            document.querySelectorAll('#sg-dyn-body .sg-dyn-val').forEach(function (el) {
                var cell = bodyRow ? bodyRow.querySelector('.' + el.dataset.key) : null;
                el.textContent = cell ? fmtR(parseFloat(cell.dataset.min)||null, parseFloat(cell.dataset.max)||null) : '—';
            });

            document.querySelectorAll('#sg-dyn-product .sg-dyn-val').forEach(function (el) {
                var cell = prodRow ? prodRow.querySelector('.' + el.dataset.key) : null;
                var v = cell ? parseFloat(cell.dataset.val)||null : null;
                el.textContent = v ? String(conv(v)) : '—';
            });
        }

        /* ── image pins ── */
        function refreshImageBadges() {
            var bodyRow = document.querySelectorAll('#sg-body-table-body .sg-row')[selIdx];
            var prodRow = document.querySelectorAll('#sg-sub-product .sg-row')[selIdx];
            var bf = {chest:'sg-body-chest',waist:'sg-body-waist',hips:'sg-body-hips',height:'sg-body-height'};
            var pf = {p_chest:'sg-prod-chest',p_waist:'sg-prod-waist',p_length:'sg-prod-length',p_shoulder:'sg-prod-shoulder'};
            document.querySelectorAll('.sg-ov-val').forEach(function (el) {
                var f = el.dataset.field;
                if (bf[f] && bodyRow) {
                    var c = bodyRow.querySelector('.' + bf[f]);
                    el.textContent = c ? fmtR(parseFloat(c.dataset.min)||null, parseFloat(c.dataset.max)||null) : '—';
                } else if (pf[f] && prodRow) {
                    var pc = prodRow.querySelector('.' + pf[f]);
                    var v  = pc ? parseFloat(pc.dataset.val)||null : null;
                    el.textContent = v ? String(conv(v)) : '—';
                } else {
                    el.textContent = '—';
                }
            });
        }

        /* ── full table unit refresh ── */
        function refreshTables() {
            document.querySelectorAll('#sg-body-table-body .sg-row').forEach(function (tr) {
                ['chest','waist','hips','height'].forEach(function (f) {
                    var c = tr.querySelector('.sg-body-' + f);
                    if (c) c.textContent = fmtR(parseFloat(c.dataset.min)||null, parseFloat(c.dataset.max)||null);
                });
            });
            document.querySelectorAll('#sg-sub-product .sg-row').forEach(function (tr) {
                ['chest','waist','length','shoulder'].forEach(function (f) {
                    var c = tr.querySelector('.sg-prod-' + f);
                    if (c) { var v = parseFloat(c.dataset.val)||null; c.textContent = v ? String(conv(v)) : '—'; }
                });
            });
        }

        /* ── recommend ── */
        function doRecommend() {
            var btn   = $id('sg-rec-btn');
            var chest = parseFloat(($id('sg-inp-chest')||{}).value)||0;
            var waist = parseFloat(($id('sg-inp-waist')||{}).value)||0;
            var hips  = parseFloat(($id('sg-inp-hips') ||{}).value)||0;
            if (unit === 'in') { chest *= 2.54; waist *= 2.54; hips *= 2.54; }
            if (btn) btn.disabled = true;
            var xsrf = (document.cookie.match(/(?:^|;\s*)XSRF-TOKEN=([^;]*)/) || [])[1];
            xsrf = xsrf ? decodeURIComponent(xsrf) : '';
            fetch('/size-guide/product/' + PID + '/recommend', {
                method: 'POST',
                headers: {'Content-Type':'application/json', Accept:'application/json', 'X-XSRF-TOKEN': xsrf},
                body: JSON.stringify({chest:chest||null, waist:waist||null, hips:hips||null}),
            })
            .then(function (r) { return r.json(); })
            .then(function (d) {
                var res = $id('sg-rec-result'), noRes = $id('sg-rec-no-result');
                if (d.size) {
                    $id('sg-rec-size').textContent = d.size;
                    $id('sg-rec-eu').textContent   = d.eu ? 'EU ' + d.eu : '';
                    if (res)   res.style.display   = 'block';
                    if (noRes) noRes.style.display = 'none';
                    document.querySelectorAll('[data-sg-size]').forEach(function (p, i) {
                        if (p.textContent.trim() === d.size) selectSize(i);
                    });
                } else {
                    if (res)   res.style.display = 'none';
                    if (noRes) noRes.style.display = 'block';
                }
            })
            .finally(function () { if (btn) btn.disabled = false; });
        }

        /* ── boot ── */
        selectSize(0);
        switchSubTab('body');
    })();
    </script>
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2643b7d197f48caff2f606750db81304)): ?>
<?php $attributes = $__attributesOriginal2643b7d197f48caff2f606750db81304; ?>
<?php unset($__attributesOriginal2643b7d197f48caff2f606750db81304); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2643b7d197f48caff2f606750db81304)): ?>
<?php $component = $__componentOriginal2643b7d197f48caff2f606750db81304; ?>
<?php unset($__componentOriginal2643b7d197f48caff2f606750db81304); ?>
<?php endif; ?>
<?php /**PATH D:\aven\packages\Webkul\SizeGuide\src\Resources\views\shop\size-guide-page.blade.php ENDPATH**/ ?>