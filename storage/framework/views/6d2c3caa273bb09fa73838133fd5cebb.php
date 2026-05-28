<?php
    $statusMap = [
        'pending'         => ['label' => 'طلب جديد',          'en' => 'New Order',        'color' => 'yellow',  'step' => 1],
        'pending_payment' => ['label' => 'في انتظار الدفع',   'en' => 'Awaiting Payment', 'color' => 'orange',  'step' => 1],
        'processing'      => ['label' => 'جاري التجهيز',      'en' => 'Processing',       'color' => 'blue',    'step' => 2],
        'completed'       => ['label' => 'تم التوصيل',        'en' => 'Delivered',        'color' => 'green',   'step' => 4],
        'canceled'        => ['label' => 'ملغي',              'en' => 'Canceled',         'color' => 'red',     'step' => 0],
        'closed'          => ['label' => 'مغلق',              'en' => 'Closed',           'color' => 'gray',    'step' => 4],
        'fraud'           => ['label' => 'قيد المراجعة',      'en' => 'Under Review',     'color' => 'gray',    'step' => 0],
    ];

    $current = $statusMap[$order->status] ?? ['label' => $order->status, 'en' => $order->status, 'color' => 'gray', 'step' => 0];

    $colorClasses = [
        'yellow' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
        'orange' => 'bg-orange-100 text-orange-800 border-orange-300',
        'blue'   => 'bg-blue-100 text-blue-800 border-blue-300',
        'green'  => 'bg-green-100 text-green-800 border-green-300',
        'red'    => 'bg-red-100 text-red-800 border-red-300',
        'gray'   => 'bg-gray-100 text-gray-800 border-gray-300',
    ];

    $timelineSteps = [
        1 => ['ar' => 'طلب جديد',       'en' => 'Order Placed',  'icon' => '📦'],
        2 => ['ar' => 'جاري التجهيز',   'en' => 'Processing',    'icon' => '⚙️'],
        3 => ['ar' => 'تم الشحن',       'en' => 'Shipped',       'icon' => '🚚'],
        4 => ['ar' => 'تم التوصيل',     'en' => 'Delivered',     'icon' => '✅'],
    ];

    $activeStep = $current['step'];
    $shipment   = $order->shipments->first();
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
        <?php echo app('translator')->get('egypt-shipping::app.track-order.result-title', ['id' => $order->increment_id]); ?>
     <?php $__env->endSlot(); ?>

    <div class="container mx-auto mt-8 mb-16 px-4 max-w-3xl">

        
        <a
            href="<?php echo e(route('egypt-shipping.track-order.index')); ?>"
            class="mb-6 inline-flex items-center gap-1 text-sm text-blue-600 hover:underline"
        >
            ← <?php echo app('translator')->get('egypt-shipping::app.track-order.back'); ?>
        </a>

        
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-xl font-bold text-gray-800 dark:text-white">
                    <?php echo app('translator')->get('egypt-shipping::app.track-order.order-number', ['id' => $order->increment_id]); ?>
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400"><?php echo e($order->created_at->format('d M Y, H:i')); ?></p>
            </div>
            <span class="rounded-full border px-4 py-1 text-sm font-semibold <?php echo e($colorClasses[$current['color']]); ?>">
                <?php echo e($current['label']); ?> &mdash; <?php echo e($current['en']); ?>

            </span>
        </div>

        
        <?php if($activeStep > 0): ?>
            <div class="mb-6 rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-900">
                <div class="flex items-center justify-between">
                    <?php $__currentLoopData = $timelineSteps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex flex-1 flex-col items-center">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full text-lg
                                <?php echo e($step <= $activeStep ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-400 dark:bg-gray-700'); ?>">
                                <?php echo e($info['icon']); ?>

                            </div>
                            <p class="mt-2 text-center text-xs font-medium
                                <?php echo e($step <= $activeStep ? 'text-blue-600' : 'text-gray-400'); ?>">
                                <?php echo e($info['ar']); ?>

                            </p>
                            <p class="text-center text-xs text-gray-400"><?php echo e($info['en']); ?></p>
                        </div>

                        <?php if(!$loop->last): ?>
                            <div class="h-0.5 flex-1 mx-1 <?php echo e($step < $activeStep ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-700'); ?>"></div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>

        
        <div class="mb-6 rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-900">
            <div class="border-b border-gray-100 px-6 py-4 dark:border-gray-700">
                <h2 class="font-semibold text-gray-800 dark:text-white"><?php echo app('translator')->get('egypt-shipping::app.track-order.items'); ?></h2>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center gap-4 px-6 py-4">
                        <?php if($item->product?->base_image_url): ?>
                            <img
                                src="<?php echo e($item->product->base_image_url); ?>"
                                alt="<?php echo e($item->name); ?>"
                                class="h-14 w-14 rounded-lg object-cover"
                            >
                        <?php else: ?>
                            <div class="flex h-14 w-14 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-400 text-xl">📦</div>
                        <?php endif; ?>
                        <div class="flex-1">
                            <p class="font-medium text-gray-800 dark:text-white"><?php echo e($item->name); ?></p>
                            <p class="text-sm text-gray-500 dark:text-gray-400"><?php echo e($item->sku); ?></p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-800 dark:text-white"><?php echo e(core()->formatPrice($item->total, $order->order_currency_code)); ?></p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">× <?php echo e((int) $item->qty_ordered); ?></p>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

            
            <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-900">
                <h2 class="mb-4 font-semibold text-gray-800 dark:text-white"><?php echo app('translator')->get('egypt-shipping::app.track-order.shipping-info'); ?></h2>

                <?php if($order->shipping_address): ?>
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        <?php echo e($order->shipping_address->first_name); ?> <?php echo e($order->shipping_address->last_name); ?><br>
                        <?php echo e($order->shipping_address->address1); ?><br>
                        <?php if($order->shipping_address->address2): ?>
                            <?php echo e($order->shipping_address->address2); ?><br>
                        <?php endif; ?>
                        <?php echo e($order->shipping_address->city); ?>, <?php echo e($order->shipping_address->state); ?><br>
                        <?php echo e($order->shipping_address->country); ?>

                    </p>
                <?php endif; ?>

                <?php if($order->shipping_title): ?>
                    <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">
                        <span class="font-medium"><?php echo app('translator')->get('egypt-shipping::app.track-order.carrier'); ?>:</span>
                        <?php echo e($order->shipping_title); ?>

                    </p>
                <?php endif; ?>

                <?php if($shipment): ?>
                    <?php if($shipment->track_number): ?>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            <span class="font-medium"><?php echo app('translator')->get('egypt-shipping::app.track-order.tracking-number'); ?>:</span>
                            <?php echo e($shipment->track_number); ?>

                        </p>
                    <?php endif; ?>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        <span class="font-medium"><?php echo app('translator')->get('egypt-shipping::app.track-order.shipped-at'); ?>:</span>
                        <?php echo e($shipment->created_at->format('d M Y')); ?>

                    </p>
                <?php endif; ?>
            </div>

            
            <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-900">
                <h2 class="mb-4 font-semibold text-gray-800 dark:text-white"><?php echo app('translator')->get('egypt-shipping::app.track-order.summary'); ?></h2>

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between text-gray-600 dark:text-gray-300">
                        <span><?php echo app('translator')->get('egypt-shipping::app.track-order.subtotal'); ?></span>
                        <span><?php echo e(core()->formatPrice($order->sub_total, $order->order_currency_code)); ?></span>
                    </div>

                    <?php if($order->shipping_amount > 0): ?>
                        <div class="flex justify-between text-gray-600 dark:text-gray-300">
                            <span><?php echo app('translator')->get('egypt-shipping::app.track-order.shipping'); ?></span>
                            <span><?php echo e(core()->formatPrice($order->shipping_amount, $order->order_currency_code)); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if($order->discount_amount > 0): ?>
                        <div class="flex justify-between text-green-600">
                            <span><?php echo app('translator')->get('egypt-shipping::app.track-order.discount'); ?></span>
                            <span>- <?php echo e(core()->formatPrice($order->discount_amount, $order->order_currency_code)); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if($order->tax_amount > 0): ?>
                        <div class="flex justify-between text-gray-600 dark:text-gray-300">
                            <span><?php echo app('translator')->get('egypt-shipping::app.track-order.tax'); ?></span>
                            <span><?php echo e(core()->formatPrice($order->tax_amount, $order->order_currency_code)); ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="flex justify-between border-t border-gray-200 pt-2 font-bold text-gray-800 dark:border-gray-600 dark:text-white">
                        <span><?php echo app('translator')->get('egypt-shipping::app.track-order.grand-total'); ?></span>
                        <span><?php echo e(core()->formatPrice($order->grand_total, $order->order_currency_code)); ?></span>
                    </div>
                </div>
            </div>
        </div>
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
<?php /**PATH D:\aven\packages\Webkul\EgyptShipping\src\Resources\views\shop\track-order\result.blade.php ENDPATH**/ ?>