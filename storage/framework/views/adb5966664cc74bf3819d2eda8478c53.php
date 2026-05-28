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
     <?php $__env->slot('title', null, []); ?> <?php echo e($post ? 'Edit Post' : 'New Post'); ?> <?php $__env->endSlot(); ?>

    <div class="flex items-center justify-between gap-4">
        <p class="text-xl font-bold text-gray-800"><?php echo e($post ? 'Edit Post' : 'New Post'); ?></p>
        <a href="<?php echo e(route('admin.blog.index')); ?>" class="secondary-button">← Back</a>
    </div>

    <?php if($errors->any()): ?>
        <div class="mt-4 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-disc pl-4">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST"
          action="<?php echo e($post ? route('admin.blog.update', $post->id) : route('admin.blog.store')); ?>"
          enctype="multipart/form-data"
          class="mt-6 space-y-6">
        <?php echo csrf_field(); ?>
        <?php if($post): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            
            <div class="space-y-6 lg:col-span-2">

                
                <div class="rounded-xl border border-gray-200 bg-white p-5">
                    <label class="mb-1 block text-sm font-semibold text-gray-700">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="<?php echo e(old('title', $post?->title)); ?>"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none"
                           required>
                </div>

                
                <div class="rounded-xl border border-gray-200 bg-white p-5">
                    <label class="mb-1 block text-sm font-semibold text-gray-700">Content <span class="text-red-500">*</span></label>
                    <textarea name="content" id="blog-content" rows="18"
                              class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none font-mono"><?php echo e(old('content', $post?->content)); ?></textarea>
                </div>

                
                <div class="rounded-xl border border-gray-200 bg-white p-5">
                    <label class="mb-1 block text-sm font-semibold text-gray-700">Excerpt</label>
                    <textarea name="excerpt" rows="3"
                              class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none"
                              maxlength="500"><?php echo e(old('excerpt', $post?->excerpt)); ?></textarea>
                    <p class="mt-1 text-xs text-gray-400">Short summary shown in blog listing (max 500 chars)</p>
                </div>

                
                <div class="rounded-xl border border-gray-200 bg-white p-5">
                    <p class="mb-4 text-sm font-semibold text-gray-700">SEO</p>
                    <div class="space-y-3">
                        <div>
                            <label class="mb-1 block text-xs text-gray-500">Meta Title</label>
                            <input type="text" name="meta_title" value="<?php echo e(old('meta_title', $post?->meta_title)); ?>"
                                   class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none"
                                   maxlength="200">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs text-gray-500">Meta Description</label>
                            <textarea name="meta_description" rows="2"
                                      class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none"
                                      maxlength="300"><?php echo e(old('meta_description', $post?->meta_description)); ?></textarea>
                        </div>
                    </div>
                </div>

            </div>

            
            <div class="space-y-6">

                
                <div class="rounded-xl border border-gray-200 bg-white p-5">
                    <p class="mb-4 text-sm font-semibold text-gray-700">Publish</p>
                    <div class="mb-4">
                        <label class="mb-1 block text-xs text-gray-500">Status</label>
                        <select name="status"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none">
                            <option value="draft"     <?php echo e(old('status', $post?->status) === 'draft'     ? 'selected' : ''); ?>>Draft</option>
                            <option value="published" <?php echo e(old('status', $post?->status) === 'published' ? 'selected' : ''); ?>>Published</option>
                        </select>
                    </div>
                    <button type="submit" class="primary-button w-full justify-center">
                        <?php echo e($post ? 'Update Post' : 'Create Post'); ?>

                    </button>
                </div>

                
                <div class="rounded-xl border border-gray-200 bg-white p-5">
                    <p class="mb-3 text-sm font-semibold text-gray-700">Featured Image</p>
                    <?php if($post?->featured_image): ?>
                        <img src="<?php echo e(Storage::url($post->featured_image)); ?>"
                             class="mb-3 w-full rounded-lg object-cover" style="max-height:160px">
                    <?php endif; ?>
                    <input type="file" name="featured_image" accept="image/*"
                           class="w-full text-sm text-gray-500 file:mr-3 file:rounded file:border-0 file:bg-indigo-50 file:px-3 file:py-1 file:text-xs file:text-indigo-700">
                    <p class="mt-1 text-xs text-gray-400">JPG, PNG, WebP · max 2 MB</p>
                </div>

                
                <div class="rounded-xl border border-gray-200 bg-white p-5">
                    <p class="mb-4 text-sm font-semibold text-gray-700">Categorization</p>
                    <div class="space-y-3">
                        <div>
                            <label class="mb-1 block text-xs text-gray-500">Category</label>
                            <input type="text" name="category" value="<?php echo e(old('category', $post?->category)); ?>"
                                   class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none"
                                   maxlength="100">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs text-gray-500">Tags</label>
                            <input type="text" name="tags"
                                   value="<?php echo e(old('tags', $post ? implode(', ', (array) $post->tags) : '')); ?>"
                                   class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none"
                                   placeholder="news, tutorial, tips">
                            <p class="mt-1 text-xs text-gray-400">Comma-separated</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
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
<?php /**PATH D:\aven\packages\Webkul\Blog\src\Resources\views\admin\create.blade.php ENDPATH**/ ?>