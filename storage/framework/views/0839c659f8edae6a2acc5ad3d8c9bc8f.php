<?php $__env->startSection('page_title', $post->meta_title ?: $post->title); ?>

<?php $__env->startPush('meta'); ?>
    <?php if($post->meta_description): ?>
        <meta name="description" content="<?php echo e($post->meta_description); ?>">
    <?php endif; ?>
    <meta property="og:title"       content="<?php echo e($post->meta_title ?: $post->title); ?>">
    <meta property="og:description" content="<?php echo e($post->meta_description ?: $post->excerpt); ?>">
    <?php if($post->featured_image): ?>
        <meta property="og:image" content="<?php echo e(Storage::url($post->featured_image)); ?>">
    <?php endif; ?>
    <meta property="og:type" content="article">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content-wrapper'); ?>
<div class="container mx-auto px-4 py-10 max-w-4xl">

    
    <nav class="mb-6 text-sm text-gray-400">
        <a href="<?php echo e(route('shop.blog.index')); ?>" class="hover:text-indigo-600">Blog</a>
        <?php if($post->category): ?>
            <span class="mx-2">/</span>
            <a href="<?php echo e(route('shop.blog.index', ['category' => $post->category])); ?>" class="hover:text-indigo-600"><?php echo e($post->category); ?></a>
        <?php endif; ?>
        <span class="mx-2">/</span>
        <span class="text-gray-600"><?php echo e(Str::limit($post->title, 50)); ?></span>
    </nav>

    
    <?php if($post->featured_image): ?>
        <img src="<?php echo e(Storage::url($post->featured_image)); ?>"
             alt="<?php echo e($post->title); ?>"
             class="mb-8 w-full rounded-2xl object-cover shadow-md" style="max-height:420px">
    <?php endif; ?>

    
    <div class="mb-4 flex flex-wrap items-center gap-3 text-sm text-gray-400">
        <?php if($post->category): ?>
            <a href="<?php echo e(route('shop.blog.index', ['category' => $post->category])); ?>"
               class="rounded-full bg-indigo-50 px-3 py-0.5 text-xs font-semibold text-indigo-600 hover:bg-indigo-100">
                <?php echo e($post->category); ?>

            </a>
        <?php endif; ?>
        <span><?php echo e($post->published_at?->format('d M Y')); ?></span>
        <span>·</span>
        <span><?php echo e($post->reading_time); ?> min read</span>
    </div>

    
    <h1 class="mb-6 text-4xl font-bold leading-tight text-gray-900"><?php echo e($post->title); ?></h1>

    
    <?php if($post->tags): ?>
        <div class="mb-6 flex flex-wrap gap-2">
            <?php $__currentLoopData = $post->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span class="rounded-full bg-gray-100 px-3 py-0.5 text-xs text-gray-500">#<?php echo e($tag); ?></span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>

    
    <div class="prose prose-gray max-w-none prose-headings:font-bold prose-a:text-indigo-600">
        <?php echo nl2br(e($post->content)); ?>

    </div>

    
    <?php if($related->isNotEmpty()): ?>
        <div class="mt-16 border-t border-gray-100 pt-10">
            <h2 class="mb-6 text-xl font-bold text-gray-900">Related Posts</h2>
            <div class="grid gap-6 sm:grid-cols-3">
                <?php $__currentLoopData = $related; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('shop.blog.show', $rel->slug)); ?>"
                       class="group flex flex-col gap-2 rounded-xl border border-gray-100 bg-white p-4 hover:shadow-md transition-shadow">
                        <?php if($rel->featured_image): ?>
                            <img src="<?php echo e(Storage::url($rel->featured_image)); ?>"
                                 class="h-32 w-full rounded-lg object-cover">
                        <?php endif; ?>
                        <p class="text-sm font-semibold text-gray-800 group-hover:text-indigo-600 transition-colors line-clamp-2">
                            <?php echo e($rel->title); ?>

                        </p>
                        <p class="text-xs text-gray-400"><?php echo e($rel->published_at?->format('d M Y')); ?></p>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('shop::layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\aven\packages\Webkul\Blog\src\Resources\views\shop\show.blade.php ENDPATH**/ ?>