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
     <?php $__env->slot('title', null, []); ?> Track Your Order <?php $__env->endSlot(); ?>

    <div class="container mx-auto px-4 py-12 max-w-lg">
        <h1 class="mb-2 text-2xl font-bold text-gray-900">Track Your Order</h1>
        <p class="mb-8 text-sm text-gray-500">Enter your order number and email address to see the latest status.</p>

        <?php if($errors->any()): ?>
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <?php echo e($errors->first()); ?>

            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('shop.order.track.result')); ?>" class="space-y-5">
            <?php echo csrf_field(); ?>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700">Order Number <span class="text-red-500">*</span></label>
                <input type="text" name="order_id" value="<?php echo e(old('order_id')); ?>" required
                       placeholder="e.g. 100000001"
                       class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700">Email Address <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="<?php echo e(old('email')); ?>" required
                       placeholder="The email used when ordering"
                       class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none">
            </div>

            <button type="submit"
                    class="w-full rounded-xl bg-navyBlue px-6 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition-colors">
                Track Order
            </button>
        </form>
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
<?php /**PATH D:\aven\packages\Webkul\OrderNotification\src\Resources\views\track\index.blade.php ENDPATH**/ ?>