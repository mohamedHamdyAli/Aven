<?php $__env->startSection('page_title', __('Blog')); ?>

<?php $__env->startSection('content-wrapper'); ?>
<div class="container mx-auto px-4 py-10 max-w-7xl">

    
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Blog</h1>
        <p class="mt-1 text-gray-500">Tips, news and updates from our team.</p>
    </div>

    
    <?php if($categories->isNotEmpty()): ?>
        <div class="mb-8 flex flex-wrap gap-2">
            <a href="<?php echo e(route('shop.blog.index')); ?>"
               class="rounded-full px-4 py-1.5 text-sm font-medium transition
                      <?php echo e(!request('category') ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'); ?>">
                All
            </a>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('shop.blog.index', ['category' => $cat])); ?>"
                   class="rounded-full px-4 py-1.5 text-sm font-medium transition
                          <?php echo e(request('category') === $cat ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'); ?>">
                    <?php echo e($cat); ?>

                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>

    
    <?php if($posts->isEmpty()): ?>
        <div class="py-20 text-center text-gray-400">No posts published yet.</div>
    <?php else: ?>
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <article class="group flex flex-col overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm hover:shadow-md transition-shadow">
                    <?php if($post->featured_image): ?>
                        <a href="<?php echo e(route('shop.blog.show', $post->slug)); ?>" class="block overflow-hidden">
                            <img src="<?php echo e(Storage::url($post->featured_image)); ?>"
                                 alt="<?php echo e($post->title); ?>"
                                 class="h-48 w-full object-cover transition-transform duration-300 group-hover:scale-105">
                        </a>
                    <?php endif; ?>
                    <div class="flex flex-1 flex-col p-5">
                        <?php if($post->category): ?>
                            <span class="mb-2 inline-block w-fit rounded-full bg-indigo-50 px-3 py-0.5 text-xs font-semibold text-indigo-600">
                                <?php echo e($post->category); ?>

                            </span>
                        <?php endif; ?>
                        <h2 class="mb-2 text-lg font-bold text-gray-900 leading-snug">
                            <a href="<?php echo e(route('shop.blog.show', $post->slug)); ?>" class="hover:text-indigo-600 transition-colors">
                                <?php echo e($post->title); ?>

                            </a>
                        </h2>
                        <?php if($post->excerpt): ?>
                            <p class="mb-4 flex-1 text-sm text-gray-500 line-clamp-3"><?php echo e($post->excerpt); ?></p>
                        <?php endif; ?>
                        <div class="mt-auto flex items-center justify-between text-xs text-gray-400">
                            <span><?php echo e($post->published_at?->format('d M Y')); ?></span>
                            <span><?php echo e($post->reading_time); ?> min read</span>
                        </div>
                    </div>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="mt-10"><?php echo e($posts->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('shop::layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\aven\packages\Webkul\Blog\src\Resources\views\shop\index.blade.php ENDPATH**/ ?>