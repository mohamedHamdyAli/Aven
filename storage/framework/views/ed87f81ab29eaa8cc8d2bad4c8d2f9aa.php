<?php if (isset($component)) { $__componentOriginal8001c520f4b7dcb40a16cd3b411856d1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8001c520f4b7dcb40a16cd3b411856d1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.layouts.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin::layouts'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('title', null, []); ?> Push Notifications <?php $__env->endSlot(); ?>

    <div class="flex items-center justify-between gap-4">
        <div>
            <p class="text-xl font-bold text-gray-800">Push Notifications</p>
            <p class="text-sm text-gray-500"><?php echo e(number_format($subscriberCount)); ?> active subscribers</p>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="mt-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <?php
        $pubKey = config('push-notification.vapid_public_key');
    ?>
    <?php if(!$pubKey): ?>
        <div class="mt-4 rounded-lg border border-yellow-200 bg-yellow-50 p-4 text-sm text-yellow-800">
            <strong>Setup required:</strong> Add <code>VAPID_PUBLIC_KEY</code> and <code>VAPID_PRIVATE_KEY</code> to your <code>.env</code> file.
            Generate them by running:<br>
            <code class="mt-1 block bg-yellow-100 px-2 py-1 rounded font-mono text-xs">php artisan push:generate-keys</code>
        </div>
    <?php endif; ?>

    
    <div class="mt-6 rounded-xl border border-gray-200 bg-white p-5">
        <p class="mb-4 text-sm font-semibold text-gray-700">Send Push Notification</p>
        <form method="POST" action="<?php echo e(route('admin.push.send')); ?>" class="space-y-4">
            <?php echo csrf_field(); ?>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-xs text-gray-500">Title</label>
                    <input type="text" name="title" maxlength="100"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" required>
                </div>
                <div>
                    <label class="mb-1 block text-xs text-gray-500">URL (on click)</label>
                    <input type="url" name="url"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                           placeholder="https://yourstore.com/sale">
                </div>
            </div>
            <div>
                <label class="mb-1 block text-xs text-gray-500">Message Body</label>
                <textarea name="body" rows="2" maxlength="255"
                          class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" required></textarea>
            </div>
            <button type="submit" class="primary-button" <?php echo e(!$pubKey ? 'disabled' : ''); ?>>
                Send to All Subscribers
            </button>
        </form>
    </div>

    
    <div class="mt-6 overflow-hidden rounded-xl border border-gray-200 bg-white">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50 text-xs text-gray-500">
                    <th class="px-4 py-3 text-left">Title</th>
                    <th class="px-4 py-3 text-left">Message</th>
                    <th class="px-4 py-3 text-center">Sent To</th>
                    <th class="px-4 py-3 text-right">Date</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $campaigns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-800"><?php echo e($c->title); ?></td>
                        <td class="px-4 py-3 text-gray-500 max-w-xs truncate"><?php echo e($c->body); ?></td>
                        <td class="px-4 py-3 text-center text-gray-600"><?php echo e(number_format($c->sent_count)); ?></td>
                        <td class="px-4 py-3 text-right text-xs text-gray-400"><?php echo e($c->created_at->format('d M Y H:i')); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="py-12 text-center text-sm text-gray-400">No campaigns sent yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4"><?php echo e($campaigns->links()); ?></div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8001c520f4b7dcb40a16cd3b411856d1)): ?>
<?php $attributes = $__attributesOriginal8001c520f4b7dcb40a16cd3b411856d1; ?>
<?php unset($__attributesOriginal8001c520f4b7dcb40a16cd3b411856d1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8001c520f4b7dcb40a16cd3b411856d1)): ?>
<?php $component = $__componentOriginal8001c520f4b7dcb40a16cd3b411856d1; ?>
<?php unset($__componentOriginal8001c520f4b7dcb40a16cd3b411856d1); ?>
<?php endif; ?>
<?php /**PATH D:\aven\packages\Webkul\PushNotification\src\Resources\views\admin\index.blade.php ENDPATH**/ ?>