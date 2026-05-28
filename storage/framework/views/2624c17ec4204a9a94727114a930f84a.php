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
     <?php $__env->slot('title', null, []); ?> Order #<?php echo e($order->increment_id); ?> — Tracking <?php $__env->endSlot(); ?>

    <div class="container mx-auto px-4 py-10 max-w-2xl">
        <div class="mb-6 flex items-center gap-3">
            <a href="<?php echo e(route('shop.order.track.index')); ?>" class="text-sm text-indigo-600 hover:underline">← Track Another Order</a>
        </div>

        <!-- Status Header -->
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Order Number</p>
                    <p class="text-xl font-bold text-gray-900">#<?php echo e($order->increment_id); ?></p>
                    <p class="mt-1 text-sm text-gray-500">Placed <?php echo e(\Carbon\Carbon::parse($order->created_at)->format('d M Y, g:i A')); ?></p>
                </div>

                <?php
                    $statusConfig = [
                        'pending'         => ['label' => 'Pending',          'color' => 'bg-yellow-100 text-yellow-800'],
                        'processing'      => ['label' => 'Processing',        'color' => 'bg-blue-100 text-blue-800'],
                        'completed'       => ['label' => 'Delivered',         'color' => 'bg-green-100 text-green-800'],
                        'closed'          => ['label' => 'Closed',            'color' => 'bg-gray-100 text-gray-700'],
                        'canceled'        => ['label' => 'Cancelled',         'color' => 'bg-red-100 text-red-700'],
                        'pending_payment' => ['label' => 'Pending Payment',   'color' => 'bg-orange-100 text-orange-700'],
                    ];
                    $sc = $statusConfig[$order->status] ?? ['label' => ucfirst($order->status), 'color' => 'bg-gray-100 text-gray-700'];
                ?>

                <span class="rounded-full px-4 py-1.5 text-sm font-semibold <?php echo e($sc['color']); ?>"><?php echo e($sc['label']); ?></span>
            </div>

            <!-- Progress Bar -->
            <?php
                $steps = ['pending', 'processing', 'completed'];
                $currentStep = array_search($order->status, $steps);
                if ($currentStep === false) $currentStep = -1;
            ?>
            <div class="mt-6 flex items-center gap-0">
                <?php $__currentLoopData = ['Order Placed', 'Processing', 'Delivered']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex flex-1 flex-col items-center">
                        <div class="h-8 w-8 rounded-full flex items-center justify-center text-sm font-bold
                            <?php echo e($i <= $currentStep ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-400'); ?>">
                            <?php echo e($i <= $currentStep ? '✓' : ($i + 1)); ?>

                        </div>
                        <p class="mt-1 text-center text-xs text-gray-500"><?php echo e($step); ?></p>
                    </div>
                    <?php if(! $loop->last): ?>
                        <div class="h-1 flex-1 <?php echo e($i < $currentStep ? 'bg-green-500' : 'bg-gray-200'); ?>"></div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- Shipment Info -->
        <?php if($shipments->isNotEmpty()): ?>
            <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-sm font-semibold text-gray-700">Shipment Details</h2>
                <?php $__currentLoopData = $shipments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shipment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <div class="flex flex-wrap justify-between gap-2 text-sm">
                            <span class="text-gray-600">Carrier: <span class="font-medium text-gray-900"><?php echo e($shipment->carrier_title ?? $shipment->carrier_code ?? 'N/A'); ?></span></span>
                            <?php if($shipment->track_number): ?>
                                <span class="text-gray-600">Tracking #: <span class="font-mono font-semibold text-indigo-700"><?php echo e($shipment->track_number); ?></span></span>
                            <?php endif; ?>
                            <span class="text-gray-500 text-xs"><?php echo e(\Carbon\Carbon::parse($shipment->created_at)->format('d M Y')); ?></span>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        <!-- Order Items -->
        <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-sm font-semibold text-gray-700">Items Ordered</h2>
            <div class="divide-y divide-gray-100">
                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center justify-between py-3">
                        <div>
                            <p class="text-sm font-medium text-gray-800"><?php echo e($item->name); ?></p>
                            <p class="text-xs text-gray-400">SKU: <?php echo e($item->sku); ?> · Qty: <?php echo e((int) $item->qty_ordered); ?></p>
                        </div>
                        <p class="text-sm font-semibold text-gray-800"><?php echo e(core()->formatPrice($item->total)); ?></p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="mt-4 flex justify-between border-t border-gray-100 pt-4">
                <span class="font-semibold text-gray-700">Total</span>
                <span class="font-bold text-gray-900"><?php echo e(core()->formatPrice($order->grand_total)); ?></span>
            </div>
        </div>

        <p class="mt-6 text-center text-xs text-gray-400">
            Need help? Contact our support team.
        </p>
    </div>
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
<?php /**PATH D:\aven\packages\Webkul\OrderNotification\src\Resources\views\track\result.blade.php ENDPATH**/ ?>