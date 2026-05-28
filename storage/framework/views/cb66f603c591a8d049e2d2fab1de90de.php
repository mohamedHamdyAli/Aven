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
     <?php $__env->slot('title', null, []); ?> Product Q&A <?php $__env->endSlot(); ?>

    <div class="flex items-center justify-between gap-4">
        <p class="text-xl font-bold text-gray-800">Product Q&amp;A</p>
        <span class="text-sm text-gray-500">
            <span class="font-semibold text-yellow-600"><?php echo e($questions->where('status', 'pending')->count()); ?></span> pending answers
        </span>
    </div>

    <div class="mt-6 space-y-4">
        <?php $__empty_1 = true; $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $statusColors = [
                    'pending'  => 'bg-yellow-100 text-yellow-700',
                    'approved' => 'bg-green-100 text-green-700',
                    'rejected' => 'bg-red-100 text-red-700',
                ];
            ?>

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <span class="rounded-full px-2 py-0.5 text-xs font-semibold <?php echo e($statusColors[$q->status]); ?>"><?php echo e(ucfirst($q->status)); ?></span>
                            <span class="text-xs text-gray-400">Product #<?php echo e($q->product_id); ?> · <?php echo e($q->customer_name); ?> · <?php echo e($q->created_at->diffForHumans()); ?></span>
                        </div>
                        <p class="mt-2 font-medium text-gray-800">Q: <?php echo e($q->question); ?></p>

                        <?php if($q->answer): ?>
                            <p class="mt-1 text-sm text-gray-600">A: <?php echo e($q->answer); ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="flex gap-2">
                        <?php if($q->status !== 'rejected'): ?>
                            <button
                                type="button"
                                class="text-xs text-red-500 hover:text-red-700"
                                onclick="if(confirm('Reject this question?')) fetch('<?php echo e(route('admin.product_qa.reject', $q->id)); ?>',{method:'POST',headers:{'X-CSRF-TOKEN':'<?php echo e(csrf_token()); ?>','Content-Type':'application/json'}}).then(()=>location.reload())"
                            >Reject</button>
                        <?php endif; ?>
                        <button
                            type="button"
                            class="text-xs text-red-400 hover:text-red-600"
                            onclick="if(confirm('Delete?')) fetch('<?php echo e(route('admin.product_qa.destroy', $q->id)); ?>',{method:'DELETE',headers:{'X-CSRF-TOKEN':'<?php echo e(csrf_token()); ?>'}}).then(()=>location.reload())"
                        >Delete</button>
                    </div>
                </div>

                <?php if($q->status === 'pending' || $q->status === 'approved'): ?>
                    <form method="POST" action="<?php echo e(route('admin.product_qa.answer', $q->id)); ?>" class="mt-4 border-t border-gray-100 pt-4">
                        <?php echo csrf_field(); ?>
                        <textarea name="answer" rows="2" required
                                  class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 outline-none"
                                  placeholder="Write your answer..."><?php echo e($q->answer); ?></textarea>
                        <div class="mt-2 flex items-center gap-3">
                            <label class="flex items-center gap-1.5 text-xs text-gray-600">
                                <input type="checkbox" name="is_published" value="1" <?php echo e($q->is_published ? 'checked' : ''); ?>>
                                Publish publicly on product page
                            </label>
                            <button type="submit" class="primary-button py-1.5 text-xs">Save Answer</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="rounded-xl border border-gray-200 bg-white py-16 text-center text-sm text-gray-400">
                No questions yet. They'll appear here when customers ask.
            </div>
        <?php endif; ?>
    </div>

    <div class="mt-6"><?php echo e($questions->links()); ?></div>
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
<?php /**PATH D:\aven\packages\Webkul\ProductQA\src\Resources\views\admin\index.blade.php ENDPATH**/ ?>