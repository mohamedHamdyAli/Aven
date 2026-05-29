<?php $reviewHelper = app('Webkul\Product\Helpers\Review'); ?>
<?php $productViewHelper = app('Webkul\Product\Helpers\View'); ?>

<?php
    $avgRatings = $reviewHelper->getAverageRating($product);

    $percentageRatings = $reviewHelper->getPercentageRating($product);

    $customAttributeValues = $productViewHelper->getAdditionalData($product);

    $attributeData = collect($customAttributeValues)->filter(fn ($item) => ! empty($item['value']));
?>

<?php
    $currentProductId = $product->id ?? null;
    if ($currentProductId) {
        $recentlyViewed = session('recently_viewed', []);
        $recentlyViewed = array_values(array_filter($recentlyViewed, fn ($id) => $id != $currentProductId));
        array_unshift($recentlyViewed, $currentProductId);
        session(['recently_viewed' => array_slice($recentlyViewed, 0, 8)]);
    }

    $recentIds = array_values(array_filter(session('recently_viewed', []), fn ($id) => $id != ($currentProductId ?? 0)));
    $recentIds = array_slice($recentIds, 0, 6);
    $recentProducts = count($recentIds) > 0
        ? \Illuminate\Support\Facades\DB::table('product_flat')
            ->whereIn('product_id', $recentIds)
            ->where('locale', app()->getLocale())
            ->where('channel', core()->getCurrentChannelCode())
            ->whereNotNull('url_key')
            ->get()
            ->keyBy('product_id')
        : collect();
?>

<?php if(core()->getConfigData('general.content.google_analytics.enabled') && core()->getConfigData('general.content.google_analytics.measurement_id')): ?>
<?php $__env->startPush('scripts'); ?>
<script>
    window.addEventListener('load', function () {
        if (typeof gtag === 'undefined') return;
        gtag('event', 'view_item', {
            currency: '<?php echo e(core()->getCurrentCurrencyCode()); ?>',
            value:    <?php echo e((float) ($product->getTypeInstance()->getMinimalPrice() ?? 0)); ?>,
            items: [{
                item_id:   '<?php echo e($product->sku); ?>',
                item_name: <?php echo json_encode($product->name, 15, 512) ?>,
                price:     <?php echo e((float) ($product->getTypeInstance()->getMinimalPrice() ?? 0)); ?>,
                quantity:  1,
            }],
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php endif; ?>

<!-- SEO Meta Content -->
<?php $__env->startPush('meta'); ?>
    <meta name="description" content="<?php echo e(trim($product->meta_description) != "" ? $product->meta_description : \Illuminate\Support\Str::limit(strip_tags($product->description), 120, '')); ?>"/>

    <meta name="keywords" content="<?php echo e($product->meta_keywords); ?>"/>

    <?php if(core()->getConfigData('catalog.rich_snippets.products.enable') !== '0'): ?>
        <script type="application/ld+json">
            <?php echo app('Webkul\Product\Helpers\SEO')->getProductJsonLd($product); ?>

        </script>
    <?php endif; ?>

    <?php $productBaseImage = product_image()->getProductBaseImage($product); ?>

    <meta name="twitter:card" content="summary_large_image" />

    <meta name="twitter:title" content="<?php echo e($product->name); ?>" />

    <meta name="twitter:description" content="<?php echo htmlspecialchars(trim(strip_tags($product->description))); ?>" />

    <meta name="twitter:image:alt" content="" />

    <meta name="twitter:image" content="<?php echo e($productBaseImage['medium_image_url']); ?>" />

    <meta property="og:type" content="og:product" />

    <meta property="og:title" content="<?php echo e($product->name); ?>" />

    <meta property="og:image" content="<?php echo e($productBaseImage['medium_image_url']); ?>" />

    <meta property="og:description" content="<?php echo htmlspecialchars(trim(strip_tags($product->description))); ?>" />

    <meta property="og:url" content="<?php echo e(route('shop.product_or_category.index', $product->url_key)); ?>" />
<?php $__env->stopPush(); ?>

<?php if(core()->getConfigData('general.content.facebook_pixel.enabled') && core()->getConfigData('general.content.facebook_pixel.pixel_id')): ?>
    <?php $__env->startPush('scripts'); ?>
        <script>
            window.addEventListener('load', function () {
                if (typeof fbq === 'undefined') return;
                fbq('track', 'ViewContent', {
                    content_ids:  ['<?php echo e($product->sku); ?>'],
                    content_name: <?php echo json_encode($product->name, 15, 512) ?>,
                    content_type: 'product',
                    value:        <?php echo e((float) $product->getTypeInstance()->getMinimalPrice()); ?>,
                    currency:     '<?php echo e(core()->getCurrentCurrencyCode()); ?>',
                });
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php endif; ?>

<!-- Page Layout -->
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
    <!-- Page Title -->
     <?php $__env->slot('title', null, []); ?> 
        <?php echo e(trim($product->meta_title) != "" ? $product->meta_title : $product->name); ?>

     <?php $__env->endSlot(); ?>

    <?php echo view_render_event('bagisto.shop.products.view.before', ['product' => $product]); ?>


    <!-- Breadcrumbs -->
    <?php if((core()->getConfigData('general.general.breadcrumbs.shop'))): ?>
        <div class="flex justify-center px-7 max-lg:hidden">
            <?php if (isset($component)) { $__componentOriginaldef12fd0653509715c3bc62a609dde73 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldef12fd0653509715c3bc62a609dde73 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.breadcrumbs.index','data' => ['name' => 'product','entity' => $product]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::breadcrumbs'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'product','entity' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($product)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldef12fd0653509715c3bc62a609dde73)): ?>
<?php $attributes = $__attributesOriginaldef12fd0653509715c3bc62a609dde73; ?>
<?php unset($__attributesOriginaldef12fd0653509715c3bc62a609dde73); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldef12fd0653509715c3bc62a609dde73)): ?>
<?php $component = $__componentOriginaldef12fd0653509715c3bc62a609dde73; ?>
<?php unset($__componentOriginaldef12fd0653509715c3bc62a609dde73); ?>
<?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Product Information Vue Component -->
    <v-product>
        <?php if (isset($component)) { $__componentOriginal0eaa493b847a30b19675cbd7b242ec38 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0eaa493b847a30b19675cbd7b242ec38 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.shimmer.products.view','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::shimmer.products.view'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0eaa493b847a30b19675cbd7b242ec38)): ?>
<?php $attributes = $__attributesOriginal0eaa493b847a30b19675cbd7b242ec38; ?>
<?php unset($__attributesOriginal0eaa493b847a30b19675cbd7b242ec38); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0eaa493b847a30b19675cbd7b242ec38)): ?>
<?php $component = $__componentOriginal0eaa493b847a30b19675cbd7b242ec38; ?>
<?php unset($__componentOriginal0eaa493b847a30b19675cbd7b242ec38); ?>
<?php endif; ?>
    </v-product>

    <!-- Information Section -->
    <div class="1180:mt-20">
        <div class="max-1180:hidden">
            <?php if (isset($component)) { $__componentOriginalfc60bb615bed00622a91d98d31176f33 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfc60bb615bed00622a91d98d31176f33 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.tabs.index','data' => ['position' => 'center','ref' => 'productTabs']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::tabs'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['position' => 'center','ref' => 'productTabs']); ?>
                <!-- Description Tab -->
                <?php echo view_render_event('bagisto.shop.products.view.description.before', ['product' => $product]); ?>


                <?php if (isset($component)) { $__componentOriginal1f42c1ae3abb1d72da6c703bc82e8a90 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1f42c1ae3abb1d72da6c703bc82e8a90 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.tabs.item','data' => ['id' => 'descritpion-tab','class' => 'container mt-[60px] !p-0','title' => trans('shop::app.products.view.description'),'isSelected' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::tabs.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'descritpion-tab','class' => 'container mt-[60px] !p-0','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('shop::app.products.view.description')),'is-selected' => true]); ?>
                    <div class="container mt-[60px] max-1180:px-5">
                        <p class="text-lg text-zinc-500 max-1180:text-sm">
                            <?php echo $product->description; ?>

                        </p>
                    </div>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1f42c1ae3abb1d72da6c703bc82e8a90)): ?>
<?php $attributes = $__attributesOriginal1f42c1ae3abb1d72da6c703bc82e8a90; ?>
<?php unset($__attributesOriginal1f42c1ae3abb1d72da6c703bc82e8a90); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1f42c1ae3abb1d72da6c703bc82e8a90)): ?>
<?php $component = $__componentOriginal1f42c1ae3abb1d72da6c703bc82e8a90; ?>
<?php unset($__componentOriginal1f42c1ae3abb1d72da6c703bc82e8a90); ?>
<?php endif; ?>

                <?php echo view_render_event('bagisto.shop.products.view.description.after', ['product' => $product]); ?>


                <!-- Additional Information Tab -->
                <?php if(count($attributeData)): ?>
                    <?php if (isset($component)) { $__componentOriginal1f42c1ae3abb1d72da6c703bc82e8a90 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1f42c1ae3abb1d72da6c703bc82e8a90 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.tabs.item','data' => ['id' => 'information-tab','class' => 'container mt-[60px] !p-0','title' => trans('shop::app.products.view.additional-information'),'isSelected' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::tabs.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'information-tab','class' => 'container mt-[60px] !p-0','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('shop::app.products.view.additional-information')),'is-selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
                        <div class="container mt-[60px] max-1180:px-5">
                            <div class="mt-8 grid max-w-max grid-cols-[auto_1fr] gap-4">
                                <?php $__currentLoopData = $customAttributeValues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customAttributeValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(! empty($customAttributeValue['value'])): ?>
                                        <div class="grid">
                                            <p class="text-base text-black">
                                                <?php echo $customAttributeValue['label']; ?>

                                            </p>
                                        </div>

                                        <?php if($customAttributeValue['type'] == 'file'): ?>
                                            <a
                                                href="<?php echo e(Storage::url($product[$customAttributeValue['code']])); ?>"
                                                download="<?php echo e($customAttributeValue['label']); ?>"
                                            >
                                                <span class="icon-download text-2xl"></span>
                                            </a>
                                        <?php elseif($customAttributeValue['type'] == 'image'): ?>
                                            <a
                                                href="<?php echo e(Storage::url($product[$customAttributeValue['code']])); ?>"
                                                download="<?php echo e($customAttributeValue['label']); ?>"
                                            >
                                                <img
                                                    class="min-h-5 min-w-5 h-5 w-5"
                                                    src="<?php echo e(Storage::url($customAttributeValue['value'])); ?>"
                                                />
                                            </a>
                                        <?php else: ?>
                                            <div class="grid">
                                                <p class="text-base text-zinc-500">
                                                    <?php echo $customAttributeValue['value']; ?>

                                                </p>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1f42c1ae3abb1d72da6c703bc82e8a90)): ?>
<?php $attributes = $__attributesOriginal1f42c1ae3abb1d72da6c703bc82e8a90; ?>
<?php unset($__attributesOriginal1f42c1ae3abb1d72da6c703bc82e8a90); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1f42c1ae3abb1d72da6c703bc82e8a90)): ?>
<?php $component = $__componentOriginal1f42c1ae3abb1d72da6c703bc82e8a90; ?>
<?php unset($__componentOriginal1f42c1ae3abb1d72da6c703bc82e8a90); ?>
<?php endif; ?>
                <?php endif; ?>

                <!-- Reviews Tab -->
                <?php if (isset($component)) { $__componentOriginal1f42c1ae3abb1d72da6c703bc82e8a90 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1f42c1ae3abb1d72da6c703bc82e8a90 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.tabs.item','data' => ['id' => 'review-tab','class' => 'container mt-[60px] !p-0','title' => trans('shop::app.products.view.review'),'isSelected' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::tabs.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'review-tab','class' => 'container mt-[60px] !p-0','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('shop::app.products.view.review')),'is-selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
                    <?php echo $__env->make('shop::products.view.reviews', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1f42c1ae3abb1d72da6c703bc82e8a90)): ?>
<?php $attributes = $__attributesOriginal1f42c1ae3abb1d72da6c703bc82e8a90; ?>
<?php unset($__attributesOriginal1f42c1ae3abb1d72da6c703bc82e8a90); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1f42c1ae3abb1d72da6c703bc82e8a90)): ?>
<?php $component = $__componentOriginal1f42c1ae3abb1d72da6c703bc82e8a90; ?>
<?php unset($__componentOriginal1f42c1ae3abb1d72da6c703bc82e8a90); ?>
<?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfc60bb615bed00622a91d98d31176f33)): ?>
<?php $attributes = $__attributesOriginalfc60bb615bed00622a91d98d31176f33; ?>
<?php unset($__attributesOriginalfc60bb615bed00622a91d98d31176f33); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfc60bb615bed00622a91d98d31176f33)): ?>
<?php $component = $__componentOriginalfc60bb615bed00622a91d98d31176f33; ?>
<?php unset($__componentOriginalfc60bb615bed00622a91d98d31176f33); ?>
<?php endif; ?>
        </div>

        <!-- Product Q&A -->
        <?php if ($__env->exists('product_qa::shop.widget', ['productId' => $product->id])) echo $__env->make('product_qa::shop.widget', ['productId' => $product->id], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    <!-- Information Section -->
    <div class="container mt-6 grid gap-3 !p-0 max-1180:px-5 1180:hidden">
        <!-- Description Accordion -->
        <?php if (isset($component)) { $__componentOriginald3ba50c765d00f082351f5b73fecce50 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald3ba50c765d00f082351f5b73fecce50 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.accordion.index','data' => ['class' => 'max-md:border-none','isActive' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::accordion'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'max-md:border-none','is-active' => true]); ?>
             <?php $__env->slot('header', null, ['class' => 'bg-gray-100 max-md:!py-3 max-sm:!py-2']); ?> 
                <p class="text-base font-medium 1180:hidden">
                    <?php echo app('translator')->get('shop::app.products.view.description'); ?>
                </p>
             <?php $__env->endSlot(); ?>

             <?php $__env->slot('content', null, ['class' => 'max-sm:px-0']); ?> 
                <div class="mb-5 text-lg text-zinc-500 max-1180:text-sm max-md:mb-1 max-md:px-4">
                    <?php echo $product->description; ?>

                </div>
             <?php $__env->endSlot(); ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald3ba50c765d00f082351f5b73fecce50)): ?>
<?php $attributes = $__attributesOriginald3ba50c765d00f082351f5b73fecce50; ?>
<?php unset($__attributesOriginald3ba50c765d00f082351f5b73fecce50); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald3ba50c765d00f082351f5b73fecce50)): ?>
<?php $component = $__componentOriginald3ba50c765d00f082351f5b73fecce50; ?>
<?php unset($__componentOriginald3ba50c765d00f082351f5b73fecce50); ?>
<?php endif; ?>

        <!-- Additional Information Accordion -->
        <?php if(count($attributeData)): ?>
            <?php if (isset($component)) { $__componentOriginald3ba50c765d00f082351f5b73fecce50 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald3ba50c765d00f082351f5b73fecce50 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.accordion.index','data' => ['class' => 'max-md:border-none','isActive' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::accordion'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'max-md:border-none','is-active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
                 <?php $__env->slot('header', null, ['class' => 'bg-gray-100 max-md:!py-3 max-sm:!py-2']); ?> 
                    <p class="text-base font-medium 1180:hidden">
                        <?php echo app('translator')->get('shop::app.products.view.additional-information'); ?>
                    </p>
                 <?php $__env->endSlot(); ?>

                 <?php $__env->slot('content', null, ['class' => 'max-sm:px-0']); ?> 
                    <div class="container max-1180:px-5">
                        <div class="grid max-w-max grid-cols-[auto_1fr] gap-4 text-lg text-zinc-500 max-1180:text-sm">
                            <?php $__currentLoopData = $customAttributeValues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customAttributeValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(! empty($customAttributeValue['value'])): ?>
                                    <div class="grid">
                                        <p
                                            class="text-base text-black"
                                            v-pre
                                        >
                                            <?php echo e($customAttributeValue['label']); ?>

                                        </p>
                                    </div>

                                    <?php if($customAttributeValue['type'] == 'file'): ?>
                                        <a
                                            href="<?php echo e(Storage::url($product[$customAttributeValue['code']])); ?>"
                                            download="<?php echo e($customAttributeValue['label']); ?>"
                                        >
                                            <span class="icon-download text-2xl"></span>
                                        </a>
                                    <?php elseif($customAttributeValue['type'] == 'image'): ?>
                                        <a
                                            href="<?php echo e(Storage::url($product[$customAttributeValue['code']])); ?>"
                                            download="<?php echo e($customAttributeValue['label']); ?>"
                                        >
                                            <img
                                                class="min-h-5 min-w-5 h-5 w-5"
                                                src="<?php echo e(Storage::url($customAttributeValue['value'])); ?>"
                                                alt="Product Image"
                                            />
                                        </a>
                                    <?php else: ?>
                                        <div class="grid">
                                            <p
                                                class="text-base text-zinc-500"
                                                v-pre
                                            >
                                                <?php echo e($customAttributeValue['value'] ?? '-'); ?>

                                            </p>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                 <?php $__env->endSlot(); ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald3ba50c765d00f082351f5b73fecce50)): ?>
<?php $attributes = $__attributesOriginald3ba50c765d00f082351f5b73fecce50; ?>
<?php unset($__attributesOriginald3ba50c765d00f082351f5b73fecce50); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald3ba50c765d00f082351f5b73fecce50)): ?>
<?php $component = $__componentOriginald3ba50c765d00f082351f5b73fecce50; ?>
<?php unset($__componentOriginald3ba50c765d00f082351f5b73fecce50); ?>
<?php endif; ?>
        <?php endif; ?>

        <!-- Reviews Accordion -->
        <?php if (isset($component)) { $__componentOriginald3ba50c765d00f082351f5b73fecce50 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald3ba50c765d00f082351f5b73fecce50 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.accordion.index','data' => ['class' => 'max-md:border-none','isActive' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::accordion'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'max-md:border-none','is-active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
             <?php $__env->slot('header', null, ['class' => 'bg-gray-100 max-md:!py-3 max-sm:!py-2','id' => 'review-accordian-button']); ?> 
                <p class="text-base font-medium">
                    <?php echo app('translator')->get('shop::app.products.view.review'); ?>
                </p>
             <?php $__env->endSlot(); ?>

             <?php $__env->slot('content', null, []); ?> 
                <?php echo $__env->make('shop::products.view.reviews', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
             <?php $__env->endSlot(); ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald3ba50c765d00f082351f5b73fecce50)): ?>
<?php $attributes = $__attributesOriginald3ba50c765d00f082351f5b73fecce50; ?>
<?php unset($__attributesOriginald3ba50c765d00f082351f5b73fecce50); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald3ba50c765d00f082351f5b73fecce50)): ?>
<?php $component = $__componentOriginald3ba50c765d00f082351f5b73fecce50; ?>
<?php unset($__componentOriginald3ba50c765d00f082351f5b73fecce50); ?>
<?php endif; ?>
    </div>

    
    <?php $relatedProducts = $product->related_products()->whereHas('product_flats', fn ($q) => $q->where('status', 1))->limit(3)->get(); ?>

    <?php if($relatedProducts->isNotEmpty()): ?>
        <div class="container mt-12 px-[60px] max-1180:px-5 max-sm:px-4">
            <h2 class="mb-6 text-2xl font-medium max-sm:text-lg">
                <?php echo app('translator')->get('shop::app.products.view.frequently-bought-together'); ?>
            </h2>

            <div class="flex flex-wrap items-center gap-4">
                
                <div class="flex w-36 flex-col items-center text-center max-sm:w-28">
                    <div class="overflow-hidden rounded-xl bg-gray-50">
                        <img src="<?php echo e($product->base_image->small_image_url ?? ''); ?>" class="aspect-square w-full object-cover" alt="<?php echo e($product->name); ?>">
                    </div>
                    <p class="mt-2 line-clamp-2 text-xs font-medium text-gray-700"><?php echo e($product->name); ?></p>
                    <p class="mt-0.5 text-xs text-gray-500"><?php echo $product->getTypeInstance()->getPriceHtml(); ?></p>
                </div>

                <?php $__currentLoopData = $relatedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="text-2xl font-light text-gray-400">+</span>

                    <a href="<?php echo e(route('shop.product_or_category.index', $related->url_key)); ?>" class="flex w-36 flex-col items-center text-center max-sm:w-28 group">
                        <div class="overflow-hidden rounded-xl bg-gray-50">
                            <img src="<?php echo e($related->base_image->small_image_url ?? ''); ?>" class="aspect-square w-full object-cover transition-transform duration-300 group-hover:scale-105" alt="<?php echo e($related->name); ?>">
                        </div>
                        <p class="mt-2 line-clamp-2 text-xs font-medium text-gray-700"><?php echo e($related->name); ?></p>
                        <p class="mt-0.5 text-xs text-gray-500"><?php echo $related->getTypeInstance()->getPriceHtml(); ?></p>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <div class="ml-2 flex flex-col items-start gap-2 max-sm:w-full max-sm:ml-0">
                    <a
                        href="<?php echo e(route('shop.checkout.onepage.index')); ?>"
                        class="primary-button whitespace-nowrap"
                    >
                        <?php echo app('translator')->get('shop::app.products.view.buy-all-together'); ?>
                    </a>
                    <p class="text-xs text-gray-400">
                        <?php echo app('translator')->get('shop::app.products.view.add-each-separately'); ?>
                    </p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Recently Viewed -->
    <v-recently-viewed></v-recently-viewed>

    <v-product-associations></v-product-associations>

    <?php echo view_render_event('bagisto.shop.products.view.after', ['product' => $product]); ?>


    <?php $__env->startPush('scripts'); ?>
        <?php if(core()->getConfigData('general.design.countdown_timer.enabled')): ?>
        <script>
            (function () {
                const key   = 'aven_ct';
                const hours = <?php echo e((int)(core()->getConfigData('general.design.countdown_timer.hours') ?? 6)); ?>;
                let end     = parseInt(localStorage.getItem(key) || '0');

                if (!end || Date.now() > end) {
                    end = Date.now() + hours * 3600000;
                    localStorage.setItem(key, end);
                }

                function pad(n) { return String(n).padStart(2, '0'); }

                function tick() {
                    const left = Math.max(0, end - Date.now());
                    if (left === 0) {
                        end = Date.now() + hours * 3600000;
                        localStorage.setItem(key, end);
                    }
                    const h = Math.floor(left / 3600000);
                    const m = Math.floor((left % 3600000) / 60000);
                    const s = Math.floor((left % 60000) / 1000);
                    const elH = document.getElementById('ct-h');
                    const elM = document.getElementById('ct-m');
                    const elS = document.getElementById('ct-s');
                    if (elH) { elH.textContent = pad(h); elM.textContent = pad(m); elS.textContent = pad(s); }
                }

                tick();
                setInterval(tick, 1000);
            })();
        </script>
        <?php endif; ?>

        
        <script>
            (function () {
                const el = document.getElementById('viewing-count');
                if (!el) return;
                const base = <?php echo e(8 + ($product->id % 17)); ?>;
                const update = () => {
                    const delta = Math.floor(Math.random() * 5) - 2;
                    const count = Math.max(6, Math.min(32, base + delta));
                    el.textContent = count;
                };
                update();
                setInterval(update, 30000);
            })();
        </script>

        
        <script>
            function flashCountdown(isoEnd) {
                return {
                    display: '--:--:--',
                    interval: null,
                    init() {
                        const end = new Date(isoEnd).getTime();
                        this.tick(end);
                        this.interval = setInterval(() => this.tick(end), 1000);
                    },
                    tick(end) {
                        const diff = end - Date.now();
                        if (diff <= 0) { this.display = 'Ended'; clearInterval(this.interval); return; }
                        const h = Math.floor(diff / 3600000);
                        const m = Math.floor((diff % 3600000) / 60000);
                        const s = Math.floor((diff % 60000) / 1000);
                        this.display = String(h).padStart(2, '0') + ':' + String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
                    },
                };
            }
        </script>

        
        <script>
            function submitStockNotify(e, productId) {
                e.preventDefault();
                var form  = e.target;
                var email = form.querySelector('[name="email"]').value;
                var msg   = document.getElementById('stock-notify-msg');

                fetch('<?php echo e(route('shop.stock_notification.store')); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                    body: JSON.stringify({ product_id: productId, email: email }),
                })
                .then(r => r.json())
                .then(function (data) {
                    form.classList.add('hidden');
                    msg.textContent = data.message;
                    msg.classList.remove('hidden');
                })
                .catch(function () {
                    msg.textContent = 'Something went wrong. Please try again.';
                    msg.classList.remove('hidden');
                });
            }
        </script>

        
        <script>
            (function () {
                const key = 'aven_rv';
                const current = {
                    id:    <?php echo e($product->id); ?>,
                    name:  <?php echo json_encode($product->name, 15, 512) ?>,
                    url:   <?php echo json_encode(route('shop.product_or_category.index', $product->url_key), 512) ?>,
                    image: <?php echo json_encode($productBaseImage['small_image_url'] ?? '', 15, 512) ?>,
                    price: <?php echo json_encode(core()->currency($product->getTypeInstance()->getMinimalPrice()), 15, 512) ?>,
                };
                try {
                    let rv = JSON.parse(localStorage.getItem(key) || '[]');
                    rv = rv.filter(p => p && p.id && p.id !== current.id);
                    rv.unshift(current);
                    localStorage.setItem(key, JSON.stringify(rv.slice(0, 10)));
                } catch (e) {}
            })();
        </script>

        <script type="text/x-template" id="v-recently-viewed-template">
            <div v-if="products.length" class="container mt-12 px-[60px] max-1180:px-5 max-sm:px-4">
                <h2 class="mb-5 text-2xl font-medium max-sm:text-lg">
                    <?php echo app('translator')->get('shop::app.products.view.recently-viewed'); ?>
                </h2>

                <div class="flex gap-4 overflow-x-auto pb-2">
                    <a
                        v-for="product in products"
                        :key="product.id"
                        :href="product.url"
                        class="w-44 flex-shrink-0 group max-sm:w-32"
                    >
                        <div class="overflow-hidden rounded-xl bg-gray-50">
                            <img
                                :src="product.image"
                                :alt="product.name"
                                class="aspect-square w-full object-cover transition-transform duration-300 group-hover:scale-105"
                            >
                        </div>
                        <p class="mt-2 line-clamp-2 text-sm font-medium text-gray-800">{{ product.name }}</p>
                        <p class="mt-0.5 text-sm text-gray-500" v-html="product.price"></p>
                    </a>
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-recently-viewed', {
                template: '#v-recently-viewed-template',

                data() {
                    return { products: [] };
                },

                mounted() {
                    try {
                        const rv = JSON.parse(localStorage.getItem('aven_rv') || '[]');
                        this.products = rv.filter(p => p && p.id && p.id !== <?php echo e($product->id); ?>);
                    } catch (e) {}
                },
            });
        </script>
    <?php $__env->stopPush(); ?>

    <?php if (! $__env->hasRenderedOnce('2e2b4e8d-9e4d-45e7-ba8d-5345731e809b')): $__env->markAsRenderedOnce('2e2b4e8d-9e4d-45e7-ba8d-5345731e809b');
$__env->startPush('scripts'); ?>
        <script
            type="text/x-template"
            id="v-product-template"
        >
            <?php if (isset($component)) { $__componentOriginal4d3fcee3e355fb6c8889181b04f357cc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4d3fcee3e355fb6c8889181b04f357cc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.form.index','data' => ['vSlot' => '{ meta, errors, handleSubmit }','as' => 'div']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['v-slot' => '{ meta, errors, handleSubmit }','as' => 'div']); ?>
                <form
                    ref="formData"
                    @submit="handleSubmit($event, addToCart)"
                >
                    <input
                        type="hidden"
                        name="product_id"
                        value="<?php echo e($product->id); ?>"
                    >

                    <input
                        type="hidden"
                        name="is_buy_now"
                        v-model="is_buy_now"
                    >

                    <div class="container px-[60px] max-1180:px-0">
                        <div class="mt-12 flex gap-9 max-1180:flex-wrap max-lg:mt-0 max-sm:gap-y-4">
                            <!-- Gallery Blade Inclusion -->
                            <?php echo $__env->make('shop::products.view.gallery', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                            <!-- Details -->
                            <div class="relative max-w-[590px] max-1180:w-full max-1180:max-w-full max-1180:px-5 max-sm:px-4">
                                <?php echo view_render_event('bagisto.shop.products.name.before', ['product' => $product]); ?>


                                <div class="flex justify-between gap-4">
                                    <h1 class="break-words text-3xl font-medium max-sm:text-xl" v-pre>
                                        <?php echo e($product->name); ?>

                                    </h1>

                                    <?php if(core()->getConfigData('customer.settings.wishlist.wishlist_option')): ?>
                                        <div
                                            class="max-sm:min-h-7 max-sm:min-w-7 flex max-h-[46px] min-h-[46px] min-w-[46px] cursor-pointer items-center justify-center rounded-full border bg-white text-2xl transition-all hover:opacity-[0.8] max-sm:max-h-7 max-sm:text-base"
                                            role="button"
                                            aria-label="<?php echo app('translator')->get('shop::app.products.view.add-to-wishlist'); ?>"
                                            tabindex="0"
                                            :class="isWishlist ? 'icon-heart-fill text-red-600' : 'icon-heart'"
                                            @click="addToWishlist"
                                        >
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <?php echo view_render_event('bagisto.shop.products.name.after', ['product' => $product]); ?>


                                <!-- Rating -->
                                <?php echo view_render_event('bagisto.shop.products.rating.before', ['product' => $product]); ?>


                                <?php if($totalRatings = $reviewHelper->getTotalFeedback($product)): ?>
                                    <!-- Scroll To Reviews Section and Activate Reviews Tab -->
                                    <div
                                        class="mt-1 w-max cursor-pointer max-sm:mt-1.5"
                                        role="button"
                                        tabindex="0"
                                        @click="scrollToReview"
                                    >
                                        <?php if (isset($component)) { $__componentOriginal189c61a27640c2633434112e6503d31c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal189c61a27640c2633434112e6503d31c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.products.ratings','data' => ['class' => 'transition-all hover:border-gray-400 max-sm:px-3 max-sm:py-1','average' => $avgRatings,'total' => $totalRatings,':rating' => 'true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::products.ratings'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'transition-all hover:border-gray-400 max-sm:px-3 max-sm:py-1','average' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($avgRatings),'total' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($totalRatings),':rating' => 'true']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal189c61a27640c2633434112e6503d31c)): ?>
<?php $attributes = $__attributesOriginal189c61a27640c2633434112e6503d31c; ?>
<?php unset($__attributesOriginal189c61a27640c2633434112e6503d31c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal189c61a27640c2633434112e6503d31c)): ?>
<?php $component = $__componentOriginal189c61a27640c2633434112e6503d31c; ?>
<?php unset($__componentOriginal189c61a27640c2633434112e6503d31c); ?>
<?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <?php echo view_render_event('bagisto.shop.products.rating.after', ['product' => $product]); ?>


                                
                                <?php
                                    $soldCount = \Illuminate\Support\Facades\DB::table('order_items')
                                        ->join('orders', 'orders.id', '=', 'order_items.order_id')
                                        ->where('order_items.product_id', $product->id)
                                        ->where('orders.created_at', '>=', now()->subHours(24))
                                        ->whereIn('orders.status', ['processing', 'completed', 'pending', 'complete'])
                                        ->sum('order_items.qty_ordered');
                                ?>

                                <div class="mt-2.5 flex flex-wrap items-center gap-x-4 gap-y-1.5 text-xs">
                                    <?php if($soldCount > 0): ?>
                                        <span class="flex items-center gap-1.5 font-medium text-orange-600">
                                            <span class="h-1.5 w-1.5 rounded-full bg-orange-500"></span>
                                            <?php echo app('translator')->get('shop::app.products.view.sold-today', ['count' => $soldCount]); ?>
                                        </span>
                                    <?php endif; ?>

                                    <span
                                        class="flex items-center gap-1.5 font-medium text-green-600"
                                        id="viewing-now-badge"
                                    >
                                        <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-green-500"></span>
                                        <span id="viewing-count">--</span>
                                        <?php echo app('translator')->get('shop::app.products.view.viewing-now'); ?>
                                    </span>
                                </div>

                                <!-- Pricing -->
                                <?php echo view_render_event('bagisto.shop.products.price.before', ['product' => $product]); ?>


                                <?php
                                    $flashSale = app(\Webkul\FlashSale\Services\FlashSaleService::class)
                                        ->getActiveForProduct($product->id);
                                ?>

                                <?php if($flashSale): ?>
                                    <div
                                        class="mt-3 flex items-center gap-2 rounded-xl bg-red-50 border border-red-200 px-4 py-2"
                                        x-data="flashCountdown('<?php echo e($flashSale->ends_at->toIso8601String()); ?>')"
                                        x-init="init()"
                                    >
                                        <span class="text-red-600 font-bold text-sm">⚡ Flash Sale ends in:</span>
                                        <span class="font-mono font-bold text-red-600 text-sm" x-text="display">--:--:--</span>
                                    </div>
                                <?php endif; ?>

                                <p class="mt-[22px] flex items-center gap-2.5 text-2xl !font-medium max-sm:mt-2 max-sm:gap-x-2.5 max-sm:gap-y-0 max-sm:text-lg">
                                    <?php echo $product->getTypeInstance()->getPriceHtml(); ?>

                                </p>

                                <?php if(\Webkul\Tax\Facades\Tax::isInclusiveTaxProductPrices()): ?>
                                    <span class="text-sm font-normal text-zinc-500 max-sm:text-xs">
                                        (<?php echo app('translator')->get('shop::app.products.view.tax-inclusive'); ?>)
                                    </span>
                                <?php endif; ?>

                                <?php if(count($product->getTypeInstance()->getCustomerGroupPricingOffers())): ?>
                                    <div class="mt-2.5 grid gap-1.5">
                                        <?php $__currentLoopData = $product->getTypeInstance()->getCustomerGroupPricingOffers(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $offer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <p class="text-zinc-500 [&>*]:text-black">
                                                <?php echo $offer; ?>

                                            </p>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php endif; ?>

                                <?php echo view_render_event('bagisto.shop.products.price.after', ['product' => $product]); ?>


                                
                                <?php $stockQty = $product->totalQuantity(); ?>

                                <?php if($stockQty > 0 && $stockQty <= 10): ?>
                                    <div class="mt-3">
                                        <?php if($stockQty <= 3): ?>
                                            <span class="inline-flex items-center gap-2 rounded-full bg-red-50 px-3 py-1 text-sm font-medium text-red-600 dark:bg-red-900/20">
                                                <span class="h-2 w-2 animate-pulse rounded-full bg-red-500"></span>
                                                <?php echo app('translator')->get('shop::app.products.view.only-left', ['qty' => $stockQty]); ?> — <?php echo app('translator')->get('shop::app.products.view.order-soon'); ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-2 rounded-full bg-orange-50 px-3 py-1 text-sm font-medium text-orange-600 dark:bg-orange-900/20">
                                                <span class="h-2 w-2 rounded-full bg-orange-400"></span>
                                                <?php echo app('translator')->get('shop::app.products.view.only-left', ['qty' => $stockQty]); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <?php echo view_render_event('bagisto.shop.products.short_description.before', ['product' => $product]); ?>


                                <p class="mt-6 text-lg text-zinc-500 max-sm:mt-1.5 max-sm:text-sm">
                                    <?php echo $product->short_description; ?>

                                </p>

                                <?php echo view_render_event('bagisto.shop.products.short_description.after', ['product' => $product]); ?>


                                <?php echo $__env->make('shop::products.view.types.simple', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                                <?php echo $__env->make('shop::products.view.types.configurable', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                                <?php echo $__env->make('shop::products.view.types.grouped', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                                <?php echo $__env->make('shop::products.view.types.bundle', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                                <?php echo $__env->make('shop::products.view.types.downloadable', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                                <?php echo $__env->make('shop::products.view.types.booking', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                                <!-- Size Guide -->
                                <?php echo $__env->make('size-guide::shop.size-guide-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                                
                                <?php if(core()->getConfigData('general.design.countdown_timer.enabled')): ?>
                                    <?php
                                        $ctMessage = core()->getConfigData('general.design.countdown_timer.message') ?? 'Limited time offer! Ends in:';
                                        $ctHours   = (int)(core()->getConfigData('general.design.countdown_timer.hours') ?? 6);
                                    ?>
                                    <div class="mt-4 flex items-center gap-3 rounded-xl border border-red-100 bg-red-50 px-4 py-2.5 max-sm:flex-wrap dark:bg-red-900/20">
                                        <span class="text-sm font-medium text-red-700 dark:text-red-400"><?php echo e($ctMessage); ?></span>
                                        <div class="flex items-center gap-1 font-mono text-base font-bold text-red-700 dark:text-red-400">
                                            <span id="ct-h" class="rounded bg-red-100 px-1.5 py-0.5">00</span>
                                            <span>:</span>
                                            <span id="ct-m" class="rounded bg-red-100 px-1.5 py-0.5">00</span>
                                            <span>:</span>
                                            <span id="ct-s" class="rounded bg-red-100 px-1.5 py-0.5">00</span>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- Product Actions and Quantity Box -->
                                <div id="atc-trigger" class="mt-8 flex max-w-[470px] gap-4 max-sm:mt-4">

                                    <?php echo view_render_event('bagisto.shop.products.view.quantity.before', ['product' => $product]); ?>


                                    <?php if($product->getTypeInstance()->showQuantityBox()): ?>
                                        <?php if (isset($component)) { $__componentOriginal6c50a43d549a14cd17ba26b5e08aa48c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6c50a43d549a14cd17ba26b5e08aa48c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.quantity-changer.index','data' => ['name' => 'quantity','value' => '1','class' => 'gap-x-4 rounded-xl px-7 py-4 max-md:py-3 max-sm:gap-x-5 max-sm:rounded-lg max-sm:px-4 max-sm:py-1.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::quantity-changer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'quantity','value' => '1','class' => 'gap-x-4 rounded-xl px-7 py-4 max-md:py-3 max-sm:gap-x-5 max-sm:rounded-lg max-sm:px-4 max-sm:py-1.5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6c50a43d549a14cd17ba26b5e08aa48c)): ?>
<?php $attributes = $__attributesOriginal6c50a43d549a14cd17ba26b5e08aa48c; ?>
<?php unset($__attributesOriginal6c50a43d549a14cd17ba26b5e08aa48c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6c50a43d549a14cd17ba26b5e08aa48c)): ?>
<?php $component = $__componentOriginal6c50a43d549a14cd17ba26b5e08aa48c; ?>
<?php unset($__componentOriginal6c50a43d549a14cd17ba26b5e08aa48c); ?>
<?php endif; ?>
                                    <?php endif; ?>

                                    <?php echo view_render_event('bagisto.shop.products.view.quantity.after', ['product' => $product]); ?>


                                    <?php if(core()->getConfigData('sales.checkout.shopping_cart.cart_page')): ?>
                                        <!-- Add To Cart Button -->
                                        <?php echo view_render_event('bagisto.shop.products.view.add_to_cart.before', ['product' => $product]); ?>


                                        <?php if (isset($component)) { $__componentOriginal30786825665921390a816ebee82cf580 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal30786825665921390a816ebee82cf580 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.button.index','data' => ['type' => 'submit','class' => 'secondary-button w-full max-w-full max-md:py-3 max-sm:rounded-lg max-sm:py-1.5','buttonType' => 'secondary-button','loading' => false,'title' => trans('shop::app.products.view.add-to-cart'),'disabled' => ! $product->isSaleable(1),':loading' => 'isStoring.addToCart',':disabled' => 'isStoring.addToCart','@click' => 'is_buy_now=0;']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','class' => 'secondary-button w-full max-w-full max-md:py-3 max-sm:rounded-lg max-sm:py-1.5','button-type' => 'secondary-button','loading' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('shop::app.products.view.add-to-cart')),'disabled' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(! $product->isSaleable(1)),':loading' => 'isStoring.addToCart',':disabled' => 'isStoring.addToCart','@click' => 'is_buy_now=0;']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal30786825665921390a816ebee82cf580)): ?>
<?php $attributes = $__attributesOriginal30786825665921390a816ebee82cf580; ?>
<?php unset($__attributesOriginal30786825665921390a816ebee82cf580); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal30786825665921390a816ebee82cf580)): ?>
<?php $component = $__componentOriginal30786825665921390a816ebee82cf580; ?>
<?php unset($__componentOriginal30786825665921390a816ebee82cf580); ?>
<?php endif; ?>

                                        <?php echo view_render_event('bagisto.shop.products.view.add_to_cart.after', ['product' => $product]); ?>

                                    <?php else: ?>
                                        <button
                                            type="button"
                                            class="secondary-button w-full max-w-full max-md:py-3 max-sm:rounded-lg max-sm:py-1.5"
                                            @click="$refs.contactUsModal.open()"
                                        >
                                            <?php echo app('translator')->get('shop::app.components.layouts.footer.contact-us'); ?>
                                        </button>
                                    <?php endif; ?>
                                </div>

                                <!-- Buy Now Button -->
                                <?php if(core()->getConfigData('sales.checkout.shopping_cart.cart_page')): ?>
                                    <?php echo view_render_event('bagisto.shop.products.view.buy_now.before', ['product' => $product]); ?>


                                    <?php if(core()->getConfigData('catalog.products.storefront.buy_now_button_display') !== '0'): ?>
                                        <!-- Go To Checkout — shown when cart already has items -->
                                        <a
                                            v-if="cartItemsQty > 0"
                                            href="<?php echo e(route('shop.checkout.onepage.index')); ?>"
                                            class="primary-button mt-5 flex w-full max-w-[470px] items-center justify-center max-md:py-3 max-sm:mt-3 max-sm:rounded-lg max-sm:py-1.5"
                                        >
                                            Continue to Checkout
                                        </a>

                                        <!-- Buy Now — shown when cart is empty -->
                                        <?php if (isset($component)) { $__componentOriginal30786825665921390a816ebee82cf580 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal30786825665921390a816ebee82cf580 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.button.index','data' => ['vElse' => true,'type' => 'submit','class' => 'primary-button mt-5 w-full max-w-[470px] max-md:py-3 max-sm:mt-3 max-sm:rounded-lg max-sm:py-1.5','buttonType' => 'primary-button','title' => trans('shop::app.products.view.buy-now'),'disabled' => ! $product->isSaleable(1),':loading' => 'isStoring.buyNow','@click' => 'is_buy_now=1;',':disabled' => 'isStoring.buyNow']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['v-else' => true,'type' => 'submit','class' => 'primary-button mt-5 w-full max-w-[470px] max-md:py-3 max-sm:mt-3 max-sm:rounded-lg max-sm:py-1.5','button-type' => 'primary-button','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('shop::app.products.view.buy-now')),'disabled' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(! $product->isSaleable(1)),':loading' => 'isStoring.buyNow','@click' => 'is_buy_now=1;',':disabled' => 'isStoring.buyNow']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal30786825665921390a816ebee82cf580)): ?>
<?php $attributes = $__attributesOriginal30786825665921390a816ebee82cf580; ?>
<?php unset($__attributesOriginal30786825665921390a816ebee82cf580); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal30786825665921390a816ebee82cf580)): ?>
<?php $component = $__componentOriginal30786825665921390a816ebee82cf580; ?>
<?php unset($__componentOriginal30786825665921390a816ebee82cf580); ?>
<?php endif; ?>
                                    <?php endif; ?>

                                    <?php echo view_render_event('bagisto.shop.products.view.buy_now.after', ['product' => $product]); ?>

                                <?php endif; ?>

                                <!-- Shop The Look -->
                                <div class="mt-6 border-t border-gray-100 pt-6 dark:border-gray-700">
                                    <?php echo $__env->make('shop-the-look::shop.product-look-section', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                </div>

                                <?php if($product->totalQuantity() <= 0): ?>
                                    <!-- Back in Stock Notification Form -->
                                    <div class="mt-5 w-full max-w-[470px] rounded-xl border border-orange-200 bg-orange-50 p-4">
                                        <p class="mb-3 text-sm font-semibold text-orange-700">
                                            📦 <?php echo app('translator')->get('shop::app.products.view.out-of-stock-notify'); ?>
                                        </p>

                                        <form
                                            id="stock-notify-form"
                                            onsubmit="submitStockNotify(event, <?php echo e($product->id); ?>)"
                                            class="flex gap-2"
                                        >
                                            <input
                                                type="email"
                                                name="email"
                                                required
                                                placeholder="<?php echo app('translator')->get('shop::app.products.view.notify-email-placeholder'); ?>"
                                                class="flex-1 rounded-lg border border-gray-200 px-3 py-2 text-sm outline-none focus:border-navyBlue focus:ring-1 focus:ring-navyBlue"
                                            />
                                            <button
                                                type="submit"
                                                class="secondary-button whitespace-nowrap"
                                            ><?php echo app('translator')->get('shop::app.products.view.notify-me'); ?></button>
                                        </form>

                                        <p id="stock-notify-msg" class="mt-2 hidden text-sm font-medium text-green-600"></p>
                                    </div>
                                <?php endif; ?>

                                <?php echo view_render_event('bagisto.shop.products.view.additional_actions.before', ['product' => $product]); ?>


                                <!-- Share Buttons -->
                                <div class="mt-10 flex gap-9 max-md:mt-4 max-md:flex-wrap max-sm:justify-center max-sm:gap-3">
                                    <?php echo view_render_event('bagisto.shop.products.view.compare.before', ['product' => $product]); ?>


                                    <div
                                        class="flex cursor-pointer items-center justify-center gap-2.5 max-sm:gap-1.5 max-sm:text-base"
                                        role="button"
                                        tabindex="0"
                                        @click="is_buy_now=0; addToCompare(<?php echo e($product->id); ?>)"
                                    >
                                        <?php if(core()->getConfigData('catalog.products.settings.compare_option')): ?>
                                            <span
                                                class="icon-compare text-2xl"
                                                role="presentation"
                                            ></span>

                                            <?php echo app('translator')->get('shop::app.products.view.compare'); ?>
                                        <?php endif; ?>
                                    </div>

                                    <?php echo view_render_event('bagisto.shop.products.view.compare.after', ['product' => $product]); ?>

                                </div>

                                <?php echo view_render_event('bagisto.shop.products.view.additional_actions.after', ['product' => $product]); ?>

                            </div>
                        </div>
                    </div>
                </form>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4d3fcee3e355fb6c8889181b04f357cc)): ?>
<?php $attributes = $__attributesOriginal4d3fcee3e355fb6c8889181b04f357cc; ?>
<?php unset($__attributesOriginal4d3fcee3e355fb6c8889181b04f357cc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4d3fcee3e355fb6c8889181b04f357cc)): ?>
<?php $component = $__componentOriginal4d3fcee3e355fb6c8889181b04f357cc; ?>
<?php unset($__componentOriginal4d3fcee3e355fb6c8889181b04f357cc); ?>
<?php endif; ?>

            <!-- Sticky Add to Cart Bar -->
            <div
                class="fixed bottom-0 left-0 right-0 z-[100] bg-white shadow-[0_-2px_16px_rgba(0,0,0,0.12)] transition-transform duration-300"
                :class="showStickyBar ? 'translate-y-0' : 'translate-y-full'"
            >
                <div class="container flex items-center justify-between gap-4 px-[60px] py-3 max-sm:px-4 max-sm:py-2">
                    <div class="flex min-w-0 items-center gap-3">
                        <?php if($product->base_image): ?>
                            <img
                                src="<?php echo e($product->base_image->small_image_url); ?>"
                                class="h-12 w-12 flex-shrink-0 rounded-lg object-cover"
                                alt="<?php echo e($product->name); ?>"
                            >
                        <?php endif; ?>

                        <div class="min-w-0">
                            <p class="truncate font-medium text-gray-900 max-sm:text-sm"><?php echo e($product->name); ?></p>
                            <p class="text-sm text-gray-500 max-sm:text-xs"><?php echo $product->getTypeInstance()->getPriceHtml(); ?></p>
                        </div>
                    </div>

                    <button
                        type="button"
                        class="primary-button flex-shrink-0 whitespace-nowrap max-sm:px-4 max-sm:py-2 max-sm:text-sm"
                        :disabled="isStoring.addToCart"
                        @click="triggerAddToCart"
                    >
                        <span v-if="! isStoring.addToCart"><?php echo app('translator')->get('shop::app.products.view.add-to-cart'); ?></span>
                        <span v-else class="icon-spinner animate-spin text-xl"></span>
                    </button>
                </div>
            </div>

            <!-- Contact Us Modal -->
            <?php if (isset($component)) { $__componentOriginal571c487d27ea546e3c8ba67e4aec0403 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal571c487d27ea546e3c8ba67e4aec0403 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.modal.index','data' => ['ref' => 'contactUsModal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['ref' => 'contactUsModal']); ?>
                 <?php $__env->slot('header', null, []); ?> 
                <h2 class="text-lg font-semibold max-md:text-base">
                        <?php echo app('translator')->get('shop::app.products.view.contact-us.title'); ?>
                    </h2>
                 <?php $__env->endSlot(); ?>

                 <?php $__env->slot('content', null, []); ?> 
                    <?php if (isset($component)) { $__componentOriginal4d3fcee3e355fb6c8889181b04f357cc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4d3fcee3e355fb6c8889181b04f357cc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.form.index','data' => ['action' => route('shop.home.contact_us.send_mail')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['action' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('shop.home.contact_us.send_mail'))]); ?>
                        <?php if (isset($component)) { $__componentOriginal578beb4bb944f523450535628cf00b41 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal578beb4bb944f523450535628cf00b41 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.form.control-group.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::form.control-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                            <?php if (isset($component)) { $__componentOriginal2f2718777649517fc23f75e819ccd670 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f2718777649517fc23f75e819ccd670 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.form.control-group.label','data' => ['class' => 'required']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::form.control-group.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'required']); ?>
                                <?php echo app('translator')->get('shop::app.products.view.contact-us.name'); ?>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2f2718777649517fc23f75e819ccd670)): ?>
<?php $attributes = $__attributesOriginal2f2718777649517fc23f75e819ccd670; ?>
<?php unset($__attributesOriginal2f2718777649517fc23f75e819ccd670); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2f2718777649517fc23f75e819ccd670)): ?>
<?php $component = $__componentOriginal2f2718777649517fc23f75e819ccd670; ?>
<?php unset($__componentOriginal2f2718777649517fc23f75e819ccd670); ?>
<?php endif; ?>

                            <?php if (isset($component)) { $__componentOriginal03b54b937596232babb8a12a8847d013 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal03b54b937596232babb8a12a8847d013 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.form.control-group.control','data' => ['type' => 'text','name' => 'name','rules' => 'required','value' => old('name'),'label' => trans('shop::app.products.view.contact-us.name'),'placeholder' => trans('shop::app.products.view.contact-us.name'),'ariaLabel' => trans('shop::app.products.view.contact-us.name'),'ariaRequired' => 'true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::form.control-group.control'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'text','name' => 'name','rules' => 'required','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('name')),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('shop::app.products.view.contact-us.name')),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('shop::app.products.view.contact-us.name')),'aria-label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('shop::app.products.view.contact-us.name')),'aria-required' => 'true']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal03b54b937596232babb8a12a8847d013)): ?>
<?php $attributes = $__attributesOriginal03b54b937596232babb8a12a8847d013; ?>
<?php unset($__attributesOriginal03b54b937596232babb8a12a8847d013); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal03b54b937596232babb8a12a8847d013)): ?>
<?php $component = $__componentOriginal03b54b937596232babb8a12a8847d013; ?>
<?php unset($__componentOriginal03b54b937596232babb8a12a8847d013); ?>
<?php endif; ?>

                            <?php if (isset($component)) { $__componentOriginal72f1d7ac608c1db7c92b56fb85299dbf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal72f1d7ac608c1db7c92b56fb85299dbf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.form.control-group.error','data' => ['controlName' => 'name']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::form.control-group.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['control-name' => 'name']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal72f1d7ac608c1db7c92b56fb85299dbf)): ?>
<?php $attributes = $__attributesOriginal72f1d7ac608c1db7c92b56fb85299dbf; ?>
<?php unset($__attributesOriginal72f1d7ac608c1db7c92b56fb85299dbf); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal72f1d7ac608c1db7c92b56fb85299dbf)): ?>
<?php $component = $__componentOriginal72f1d7ac608c1db7c92b56fb85299dbf; ?>
<?php unset($__componentOriginal72f1d7ac608c1db7c92b56fb85299dbf); ?>
<?php endif; ?>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal578beb4bb944f523450535628cf00b41)): ?>
<?php $attributes = $__attributesOriginal578beb4bb944f523450535628cf00b41; ?>
<?php unset($__attributesOriginal578beb4bb944f523450535628cf00b41); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal578beb4bb944f523450535628cf00b41)): ?>
<?php $component = $__componentOriginal578beb4bb944f523450535628cf00b41; ?>
<?php unset($__componentOriginal578beb4bb944f523450535628cf00b41); ?>
<?php endif; ?>

                        <?php if (isset($component)) { $__componentOriginal578beb4bb944f523450535628cf00b41 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal578beb4bb944f523450535628cf00b41 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.form.control-group.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::form.control-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                            <?php if (isset($component)) { $__componentOriginal2f2718777649517fc23f75e819ccd670 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f2718777649517fc23f75e819ccd670 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.form.control-group.label','data' => ['class' => 'required']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::form.control-group.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'required']); ?>
                                <?php echo app('translator')->get('shop::app.products.view.contact-us.email'); ?>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2f2718777649517fc23f75e819ccd670)): ?>
<?php $attributes = $__attributesOriginal2f2718777649517fc23f75e819ccd670; ?>
<?php unset($__attributesOriginal2f2718777649517fc23f75e819ccd670); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2f2718777649517fc23f75e819ccd670)): ?>
<?php $component = $__componentOriginal2f2718777649517fc23f75e819ccd670; ?>
<?php unset($__componentOriginal2f2718777649517fc23f75e819ccd670); ?>
<?php endif; ?>

                            <?php if (isset($component)) { $__componentOriginal03b54b937596232babb8a12a8847d013 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal03b54b937596232babb8a12a8847d013 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.form.control-group.control','data' => ['type' => 'email','name' => 'email','rules' => 'required|email','value' => old('email'),'label' => trans('shop::app.products.view.contact-us.email'),'placeholder' => trans('shop::app.products.view.contact-us.email'),'ariaLabel' => trans('shop::app.products.view.contact-us.email'),'ariaRequired' => 'true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::form.control-group.control'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'email','name' => 'email','rules' => 'required|email','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('email')),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('shop::app.products.view.contact-us.email')),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('shop::app.products.view.contact-us.email')),'aria-label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('shop::app.products.view.contact-us.email')),'aria-required' => 'true']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal03b54b937596232babb8a12a8847d013)): ?>
<?php $attributes = $__attributesOriginal03b54b937596232babb8a12a8847d013; ?>
<?php unset($__attributesOriginal03b54b937596232babb8a12a8847d013); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal03b54b937596232babb8a12a8847d013)): ?>
<?php $component = $__componentOriginal03b54b937596232babb8a12a8847d013; ?>
<?php unset($__componentOriginal03b54b937596232babb8a12a8847d013); ?>
<?php endif; ?>

                            <?php if (isset($component)) { $__componentOriginal72f1d7ac608c1db7c92b56fb85299dbf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal72f1d7ac608c1db7c92b56fb85299dbf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.form.control-group.error','data' => ['controlName' => 'email']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::form.control-group.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['control-name' => 'email']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal72f1d7ac608c1db7c92b56fb85299dbf)): ?>
<?php $attributes = $__attributesOriginal72f1d7ac608c1db7c92b56fb85299dbf; ?>
<?php unset($__attributesOriginal72f1d7ac608c1db7c92b56fb85299dbf); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal72f1d7ac608c1db7c92b56fb85299dbf)): ?>
<?php $component = $__componentOriginal72f1d7ac608c1db7c92b56fb85299dbf; ?>
<?php unset($__componentOriginal72f1d7ac608c1db7c92b56fb85299dbf); ?>
<?php endif; ?>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal578beb4bb944f523450535628cf00b41)): ?>
<?php $attributes = $__attributesOriginal578beb4bb944f523450535628cf00b41; ?>
<?php unset($__attributesOriginal578beb4bb944f523450535628cf00b41); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal578beb4bb944f523450535628cf00b41)): ?>
<?php $component = $__componentOriginal578beb4bb944f523450535628cf00b41; ?>
<?php unset($__componentOriginal578beb4bb944f523450535628cf00b41); ?>
<?php endif; ?>

                        <?php if (isset($component)) { $__componentOriginal578beb4bb944f523450535628cf00b41 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal578beb4bb944f523450535628cf00b41 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.form.control-group.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::form.control-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                            <?php if (isset($component)) { $__componentOriginal2f2718777649517fc23f75e819ccd670 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f2718777649517fc23f75e819ccd670 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.form.control-group.label','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::form.control-group.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                <?php echo app('translator')->get('shop::app.products.view.contact-us.phone-number'); ?>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2f2718777649517fc23f75e819ccd670)): ?>
<?php $attributes = $__attributesOriginal2f2718777649517fc23f75e819ccd670; ?>
<?php unset($__attributesOriginal2f2718777649517fc23f75e819ccd670); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2f2718777649517fc23f75e819ccd670)): ?>
<?php $component = $__componentOriginal2f2718777649517fc23f75e819ccd670; ?>
<?php unset($__componentOriginal2f2718777649517fc23f75e819ccd670); ?>
<?php endif; ?>

                            <?php if (isset($component)) { $__componentOriginal03b54b937596232babb8a12a8847d013 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal03b54b937596232babb8a12a8847d013 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.form.control-group.control','data' => ['type' => 'text','name' => 'contact','rules' => 'phone','value' => old('contact'),'label' => trans('shop::app.products.view.contact-us.phone-number'),'placeholder' => trans('shop::app.products.view.contact-us.phone-number'),'ariaLabel' => trans('shop::app.products.view.contact-us.phone-number')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::form.control-group.control'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'text','name' => 'contact','rules' => 'phone','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('contact')),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('shop::app.products.view.contact-us.phone-number')),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('shop::app.products.view.contact-us.phone-number')),'aria-label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('shop::app.products.view.contact-us.phone-number'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal03b54b937596232babb8a12a8847d013)): ?>
<?php $attributes = $__attributesOriginal03b54b937596232babb8a12a8847d013; ?>
<?php unset($__attributesOriginal03b54b937596232babb8a12a8847d013); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal03b54b937596232babb8a12a8847d013)): ?>
<?php $component = $__componentOriginal03b54b937596232babb8a12a8847d013; ?>
<?php unset($__componentOriginal03b54b937596232babb8a12a8847d013); ?>
<?php endif; ?>

                            <?php if (isset($component)) { $__componentOriginal72f1d7ac608c1db7c92b56fb85299dbf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal72f1d7ac608c1db7c92b56fb85299dbf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.form.control-group.error','data' => ['controlName' => 'contact']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::form.control-group.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['control-name' => 'contact']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal72f1d7ac608c1db7c92b56fb85299dbf)): ?>
<?php $attributes = $__attributesOriginal72f1d7ac608c1db7c92b56fb85299dbf; ?>
<?php unset($__attributesOriginal72f1d7ac608c1db7c92b56fb85299dbf); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal72f1d7ac608c1db7c92b56fb85299dbf)): ?>
<?php $component = $__componentOriginal72f1d7ac608c1db7c92b56fb85299dbf; ?>
<?php unset($__componentOriginal72f1d7ac608c1db7c92b56fb85299dbf); ?>
<?php endif; ?>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal578beb4bb944f523450535628cf00b41)): ?>
<?php $attributes = $__attributesOriginal578beb4bb944f523450535628cf00b41; ?>
<?php unset($__attributesOriginal578beb4bb944f523450535628cf00b41); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal578beb4bb944f523450535628cf00b41)): ?>
<?php $component = $__componentOriginal578beb4bb944f523450535628cf00b41; ?>
<?php unset($__componentOriginal578beb4bb944f523450535628cf00b41); ?>
<?php endif; ?>

                        <?php if (isset($component)) { $__componentOriginal578beb4bb944f523450535628cf00b41 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal578beb4bb944f523450535628cf00b41 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.form.control-group.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::form.control-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                            <?php if (isset($component)) { $__componentOriginal2f2718777649517fc23f75e819ccd670 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f2718777649517fc23f75e819ccd670 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.form.control-group.label','data' => ['class' => 'required']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::form.control-group.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'required']); ?>
                                <?php echo app('translator')->get('shop::app.products.view.contact-us.desc'); ?>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2f2718777649517fc23f75e819ccd670)): ?>
<?php $attributes = $__attributesOriginal2f2718777649517fc23f75e819ccd670; ?>
<?php unset($__attributesOriginal2f2718777649517fc23f75e819ccd670); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2f2718777649517fc23f75e819ccd670)): ?>
<?php $component = $__componentOriginal2f2718777649517fc23f75e819ccd670; ?>
<?php unset($__componentOriginal2f2718777649517fc23f75e819ccd670); ?>
<?php endif; ?>

                            <?php if (isset($component)) { $__componentOriginal03b54b937596232babb8a12a8847d013 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal03b54b937596232babb8a12a8847d013 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.form.control-group.control','data' => ['type' => 'textarea','name' => 'message','rules' => 'required','label' => trans('shop::app.products.view.contact-us.message'),'placeholder' => trans('shop::app.products.view.contact-us.describe-here'),'ariaLabel' => trans('shop::app.products.view.contact-us.message'),'ariaRequired' => 'true','rows' => '6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::form.control-group.control'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'textarea','name' => 'message','rules' => 'required','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('shop::app.products.view.contact-us.message')),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('shop::app.products.view.contact-us.describe-here')),'aria-label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('shop::app.products.view.contact-us.message')),'aria-required' => 'true','rows' => '6']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal03b54b937596232babb8a12a8847d013)): ?>
<?php $attributes = $__attributesOriginal03b54b937596232babb8a12a8847d013; ?>
<?php unset($__attributesOriginal03b54b937596232babb8a12a8847d013); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal03b54b937596232babb8a12a8847d013)): ?>
<?php $component = $__componentOriginal03b54b937596232babb8a12a8847d013; ?>
<?php unset($__componentOriginal03b54b937596232babb8a12a8847d013); ?>
<?php endif; ?>

                            <?php if (isset($component)) { $__componentOriginal72f1d7ac608c1db7c92b56fb85299dbf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal72f1d7ac608c1db7c92b56fb85299dbf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.form.control-group.error','data' => ['controlName' => 'message']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::form.control-group.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['control-name' => 'message']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal72f1d7ac608c1db7c92b56fb85299dbf)): ?>
<?php $attributes = $__attributesOriginal72f1d7ac608c1db7c92b56fb85299dbf; ?>
<?php unset($__attributesOriginal72f1d7ac608c1db7c92b56fb85299dbf); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal72f1d7ac608c1db7c92b56fb85299dbf)): ?>
<?php $component = $__componentOriginal72f1d7ac608c1db7c92b56fb85299dbf; ?>
<?php unset($__componentOriginal72f1d7ac608c1db7c92b56fb85299dbf); ?>
<?php endif; ?>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal578beb4bb944f523450535628cf00b41)): ?>
<?php $attributes = $__attributesOriginal578beb4bb944f523450535628cf00b41; ?>
<?php unset($__attributesOriginal578beb4bb944f523450535628cf00b41); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal578beb4bb944f523450535628cf00b41)): ?>
<?php $component = $__componentOriginal578beb4bb944f523450535628cf00b41; ?>
<?php unset($__componentOriginal578beb4bb944f523450535628cf00b41); ?>
<?php endif; ?>

                        <?php if(core()->getConfigData('customer.captcha.credentials.status')): ?>
                            <?php if (isset($component)) { $__componentOriginal578beb4bb944f523450535628cf00b41 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal578beb4bb944f523450535628cf00b41 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.form.control-group.index','data' => ['class' => 'mt-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::form.control-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mt-5']); ?>
                                <?php echo \Webkul\Customer\Facades\Captcha::render(); ?>


                                <?php if (isset($component)) { $__componentOriginal72f1d7ac608c1db7c92b56fb85299dbf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal72f1d7ac608c1db7c92b56fb85299dbf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.form.control-group.error','data' => ['controlName' => 'recaptcha_token']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::form.control-group.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['control-name' => 'recaptcha_token']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal72f1d7ac608c1db7c92b56fb85299dbf)): ?>
<?php $attributes = $__attributesOriginal72f1d7ac608c1db7c92b56fb85299dbf; ?>
<?php unset($__attributesOriginal72f1d7ac608c1db7c92b56fb85299dbf); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal72f1d7ac608c1db7c92b56fb85299dbf)): ?>
<?php $component = $__componentOriginal72f1d7ac608c1db7c92b56fb85299dbf; ?>
<?php unset($__componentOriginal72f1d7ac608c1db7c92b56fb85299dbf); ?>
<?php endif; ?>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal578beb4bb944f523450535628cf00b41)): ?>
<?php $attributes = $__attributesOriginal578beb4bb944f523450535628cf00b41; ?>
<?php unset($__attributesOriginal578beb4bb944f523450535628cf00b41); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal578beb4bb944f523450535628cf00b41)): ?>
<?php $component = $__componentOriginal578beb4bb944f523450535628cf00b41; ?>
<?php unset($__componentOriginal578beb4bb944f523450535628cf00b41); ?>
<?php endif; ?>
                        <?php endif; ?>

                        <div class="mt-6 flex justify-end">
                            <button
                                type="submit"
                                class="primary-button rounded-2xl px-8 py-3 max-sm:rounded-lg max-sm:px-6 max-sm:py-2"
                            >
                                <?php echo app('translator')->get('shop::app.products.view.contact-us.submit'); ?>
                            </button>
                        </div>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4d3fcee3e355fb6c8889181b04f357cc)): ?>
<?php $attributes = $__attributesOriginal4d3fcee3e355fb6c8889181b04f357cc; ?>
<?php unset($__attributesOriginal4d3fcee3e355fb6c8889181b04f357cc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4d3fcee3e355fb6c8889181b04f357cc)): ?>
<?php $component = $__componentOriginal4d3fcee3e355fb6c8889181b04f357cc; ?>
<?php unset($__componentOriginal4d3fcee3e355fb6c8889181b04f357cc); ?>
<?php endif; ?>
                 <?php $__env->endSlot(); ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal571c487d27ea546e3c8ba67e4aec0403)): ?>
<?php $attributes = $__attributesOriginal571c487d27ea546e3c8ba67e4aec0403; ?>
<?php unset($__attributesOriginal571c487d27ea546e3c8ba67e4aec0403); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal571c487d27ea546e3c8ba67e4aec0403)): ?>
<?php $component = $__componentOriginal571c487d27ea546e3c8ba67e4aec0403; ?>
<?php unset($__componentOriginal571c487d27ea546e3c8ba67e4aec0403); ?>
<?php endif; ?>
        </script>

        <script type="module">
            app.component('v-product', {
                template: '#v-product-template',

                data() {
                    return {
                        isWishlist: false,

                        isCustomer: '<?php echo e(auth()->guard('customer')->check()); ?>',

                        is_buy_now: 0,

                        isStoring: {
                            addToCart: false,

                            buyNow: false,
                        },

                        showStickyBar: false,

                        cartItemsQty: 0,
                    }
                },

                mounted() {
                    this.checkWishlistStatus();

                    this.$nextTick(() => {
                        const trigger = document.getElementById('atc-trigger');

                        if (trigger) {
                            const observer = new IntersectionObserver(
                                ([entry]) => { this.showStickyBar = ! entry.isIntersecting; },
                                { threshold: 0 }
                            );

                            observer.observe(trigger);
                        }
                    });

                    this.$axios.get('<?php echo e(route('shop.api.checkout.cart.index')); ?>')
                        .then(r => { this.cartItemsQty = r.data?.data?.items_qty || 0; })
                        .catch(() => {});

                    this.$emitter.on('update-mini-cart', (cart) => {
                        this.cartItemsQty = cart?.items_qty || 0;
                    });
                },

                methods: {
                    triggerAddToCart() {
                        this.is_buy_now = 0;
                        this.$refs.formData.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
                    },

                    addToCart(params) {
                        const operation = this.is_buy_now ? 'buyNow' : 'addToCart';

                        this.isStoring[operation] = true;

                        let formData = new FormData(this.$refs.formData);

                        this.ensureQuantity(formData);

                        this.$axios.post('<?php echo e(route("shop.api.checkout.cart.store")); ?>', formData, {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            })
                            .then(response => {
                                if (response.data.message) {
                                    this.$emitter.emit('update-mini-cart', response.data.data);

                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                    if (response.data.redirect) {
                                        window.location.href= response.data.redirect;
                                    }
                                } else {
                                    this.$emitter.emit('add-flash', { type: 'warning', message: response.data.data.message });
                                }

                                this.isStoring[operation] = false;
                            })
                            .catch(error => {
                                this.isStoring[operation] = false;

                                this.$emitter.emit('add-flash', { type: 'warning', message: error.response.data.message });
                            });
                    },

                    checkWishlistStatus() {
                        if (this.isCustomer) {
                            /**
                             * Fetches the wishlist items for the customer and checks whether the current
                             * product exists in the wishlist. If found, `isWishlist` is set to true;
                             * otherwise, it is set to false.
                             *
                             * This approach is used due to Full Page Cache (FPC) limitations. We cannot
                             * use a replacer here because `product_id` is dynamic, and the replacer
                             * cannot reliably detect it.
                             */
                            this.$axios.get('<?php echo e(route('shop.api.customers.account.wishlist.index')); ?>')
                                .then(response => {
                                    const wishlistItems = response.data.data || [];

                                    this.isWishlist = Boolean(wishlistItems.find(item => item.product.id == "<?php echo e($product->id); ?>")?.product?.is_wishlist);
                                })
                                .catch(error => {});
                        }
                    },

                    addToWishlist() {
                        if (this.isCustomer) {
                            this.$axios.post('<?php echo e(route('shop.api.customers.account.wishlist.store')); ?>', {
                                    product_id: "<?php echo e($product->id); ?>"
                                })
                                .then(response => {
                                    this.isWishlist = ! this.isWishlist;

                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });
                                })
                                .catch(error => {});
                        } else {
                            window.location.href = "<?php echo e(route('shop.customer.session.index')); ?>";
                        }
                    },

                    addToCompare(productId) {
                        /**
                         * This will handle for customers.
                         */
                        if (this.isCustomer) {
                            this.$axios.post('<?php echo e(route("shop.api.compare.store")); ?>', {
                                    'product_id': productId
                                })
                                .then(response => {
                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });
                                })
                                .catch(error => {
                                    if ([400, 422].includes(error.response.status)) {
                                        this.$emitter.emit('add-flash', { type: 'warning', message: error.response.data.data.message });

                                        return;
                                    }

                                    this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message});
                                });

                            return;
                        }

                        /**
                         * This will handle for guests.
                         */
                        let existingItems = this.getStorageValue(this.getCompareItemsStorageKey()) ?? [];

                        if (existingItems.length) {
                            if (! existingItems.includes(productId)) {
                                existingItems.push(productId);

                                this.setStorageValue(this.getCompareItemsStorageKey(), existingItems);

                                this.$emitter.emit('add-flash', { type: 'success', message: "<?php echo app('translator')->get('shop::app.products.view.add-to-compare'); ?>" });
                            } else {
                                this.$emitter.emit('add-flash', { type: 'warning', message: "<?php echo app('translator')->get('shop::app.products.view.already-in-compare'); ?>" });
                            }
                        } else {
                            this.setStorageValue(this.getCompareItemsStorageKey(), [productId]);

                            this.$emitter.emit('add-flash', { type: 'success', message: "<?php echo app('translator')->get('shop::app.products.view.add-to-compare'); ?>" });
                        }
                    },

                    updateQty(quantity, id) {
                        this.isLoading = true;

                        let qty = {};

                        qty[id] = quantity;

                        this.$axios.put('<?php echo e(route('shop.api.checkout.cart.update')); ?>', { qty })
                            .then(response => {
                                if (response.data.message) {
                                    this.cart = response.data.data;
                                } else {
                                    this.$emitter.emit('add-flash', { type: 'warning', message: response.data.data.message });
                                }

                                this.isLoading = false;
                            }).catch(error => this.isLoading = false);
                    },

                    getCompareItemsStorageKey() {
                        return 'compare_items';
                    },

                    setStorageValue(key, value) {
                        localStorage.setItem(key, JSON.stringify(value));
                    },

                    getStorageValue(key) {
                        let value = localStorage.getItem(key);

                        if (value) {
                            value = JSON.parse(value);
                        }

                        return value;
                    },

                    scrollToReview() {
                        let accordianElement = document.querySelector('#review-accordian-button');

                        if (accordianElement) {
                            accordianElement.click();

                            accordianElement.scrollIntoView({
                                behavior: 'smooth'
                            });
                        }

                        let tabElement = document.querySelector('#review-tab-button');

                        if (tabElement) {
                            tabElement.click();

                            tabElement.scrollIntoView({
                                behavior: 'smooth'
                            });
                        }
                    },

                    ensureQuantity(formData) {
                        if (! formData.has('quantity')) {
                            formData.append('quantity', 1);
                        }
                    },
                },
            });
        </script>

        <script
            type="text/x-template"
            id="v-product-associations-template"
        >
            <div ref="carouselWrapper">
                <template v-if="isVisible">
                    <!-- Featured Products -->
                    <?php if (isset($component)) { $__componentOriginalc7b94830d947988d2b7058066254da2b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7b94830d947988d2b7058066254da2b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.products.carousel','data' => ['title' => trans('shop::app.products.view.related-product-title'),'src' => route('shop.api.products.related.index', ['id' => $product->id])]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::products.carousel'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('shop::app.products.view.related-product-title')),'src' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('shop.api.products.related.index', ['id' => $product->id]))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc7b94830d947988d2b7058066254da2b)): ?>
<?php $attributes = $__attributesOriginalc7b94830d947988d2b7058066254da2b; ?>
<?php unset($__attributesOriginalc7b94830d947988d2b7058066254da2b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc7b94830d947988d2b7058066254da2b)): ?>
<?php $component = $__componentOriginalc7b94830d947988d2b7058066254da2b; ?>
<?php unset($__componentOriginalc7b94830d947988d2b7058066254da2b); ?>
<?php endif; ?>

                    <!-- Up-sell Products -->
                    <?php if (isset($component)) { $__componentOriginalc7b94830d947988d2b7058066254da2b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7b94830d947988d2b7058066254da2b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.products.carousel','data' => ['title' => trans('shop::app.products.view.up-sell-title'),'src' => route('shop.api.products.up-sell.index', ['id' => $product->id])]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::products.carousel'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('shop::app.products.view.up-sell-title')),'src' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('shop.api.products.up-sell.index', ['id' => $product->id]))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc7b94830d947988d2b7058066254da2b)): ?>
<?php $attributes = $__attributesOriginalc7b94830d947988d2b7058066254da2b; ?>
<?php unset($__attributesOriginalc7b94830d947988d2b7058066254da2b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc7b94830d947988d2b7058066254da2b)): ?>
<?php $component = $__componentOriginalc7b94830d947988d2b7058066254da2b; ?>
<?php unset($__componentOriginalc7b94830d947988d2b7058066254da2b); ?>
<?php endif; ?>
                </template>
            </div>
        </script>

        <script type="module">
            app.component('v-product-associations', {
                template: '#v-product-associations-template',

                data() {
                    return {
                        isVisible: false,
                    };
                },

                mounted() {
                    const observer = new IntersectionObserver(
                        (entries) => {
                            entries.forEach((entry) => {
                                if (entry.isIntersecting) {
                                    this.isVisible = true;
                                    observer.unobserve(entry.target); // Stop observing
                                }
                            });
                        },
                        { threshold: 0.1 }
                    );

                    observer.observe(this.$refs.carouselWrapper);
                }
            });
        </script>

        <?php if(core()->getConfigData('customer.captcha.credentials.status')): ?>
            <?php echo \Webkul\Customer\Facades\Captcha::renderJS(); ?>

        <?php endif; ?>
    <?php $__env->stopPush(); endif; ?>

    
    <?php if($recentProducts->count() > 0): ?>
        <div class="container mx-auto mt-10 px-4 py-6">
            <h2 class="mb-4 text-xl font-bold text-navyBlue">Recently Viewed</h2>
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6">
                <?php $__currentLoopData = $recentIds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rid): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(isset($recentProducts[$rid])): ?>
                        <?php $rp = $recentProducts[$rid]; ?>
                        <a href="<?php echo e(url('/' . $rp->url_key)); ?>" class="group block overflow-hidden rounded-lg border border-gray-100 p-3 transition hover:shadow-md">
                            <?php $rpImg = \Illuminate\Support\Facades\DB::table('product_images')->where('product_id', $rp->product_id)->orderBy('id')->value('path'); ?>
                            <?php if($rpImg): ?>
                                <img src="<?php echo e(\Illuminate\Support\Facades\Storage::url($rpImg)); ?>" alt="<?php echo e($rp->name); ?>" class="mb-2 h-28 w-full rounded object-contain"/>
                            <?php endif; ?>
                            <p class="line-clamp-2 text-xs font-medium leading-tight text-gray-800"><?php echo e($rp->name); ?></p>
                            <p class="mt-1 text-sm font-bold text-navyBlue"><?php echo e(core()->currency($rp->price)); ?></p>
                        </a>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php endif; ?>
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
<?php /**PATH D:\aven\packages\Webkul\Shop\src/resources/views/products/view.blade.php ENDPATH**/ ?>