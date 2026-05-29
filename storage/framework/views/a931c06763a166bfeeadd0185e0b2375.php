<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'hasHeader'  => true,
    'hasFeature' => true,
    'hasFooter'  => true,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'hasHeader'  => true,
    'hasFeature' => true,
    'hasFooter'  => true,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<!DOCTYPE html>

<html
    lang="<?php echo e(app()->getLocale()); ?>"
    dir="<?php echo e(core()->getCurrentLocale()->direction); ?>"
>
    <head>

        <?php echo view_render_event('bagisto.shop.layout.head.before'); ?>


        <title><?php echo e($title ?? ''); ?></title>

        <meta charset="UTF-8">

        <meta
            http-equiv="X-UA-Compatible"
            content="IE=edge"
        >
        <meta
            http-equiv="content-language"
            content="<?php echo e(app()->getLocale()); ?>"
        >

        <meta
            name="viewport"
            content="width=device-width, initial-scale=1"
        >
        <meta
            name="base-url"
            content="<?php echo e(url()->to('/')); ?>"
        >
        <meta
            name="csrf-token"
            content="<?php echo e(csrf_token()); ?>"
        >
        <meta
            name="currency"
            content="<?php echo e(core()->getCurrentCurrency()->toJson()); ?>"
        >
        <meta 
            name="generator" 
            content="Bagisto"
        >

        <?php echo $__env->yieldPushContent('meta'); ?>

        <link
            rel="icon"
            sizes="32x32"
            href="<?php echo e(core()->getCurrentChannel()->favicon_url ?? bagisto_asset('images/favicon.ico')); ?>"
        />

        <?php echo themes()->setBagistoVite(['src/Resources/assets/css/app.css', 'src/Resources/assets/js/app.js'])->toHtml(); ?>

        <link
            rel="preconnect"
            href="https://fonts.googleapis.com"
            crossorigin
        />

        <link
            rel="preconnect"
            href="https://fonts.gstatic.com"
            crossorigin
        />

        <?php
            $bodyFamily    = core()->getConfigData('general.design.shop_fonts.body_family')    ?? 'Poppins';
            $headingFamily = core()->getConfigData('general.design.shop_fonts.heading_family') ?? 'DM Serif Display';
            $navFamily     = core()->getConfigData('general.design.shop_fonts.nav_family')     ?? 'Poppins';
            $bodyWeight    = core()->getConfigData('general.design.shop_fonts.body_weight')    ?? '400';
            $headingWeight = core()->getConfigData('general.design.shop_fonts.heading_weight') ?? '700';
            $navWeight     = core()->getConfigData('general.design.shop_fonts.nav_weight')     ?? '500';
            $googleFonts   = collect([$bodyFamily, $headingFamily, $navFamily])
                ->filter()
                ->unique()
                ->map(fn($f) => str_replace(' ', '+', $f) . ':wght@300;400;500;600;700;800;900')
                ->implode('&family=');
            $googleFontsUrl = 'https://fonts.googleapis.com/css2?family=' . $googleFonts . '&display=swap';
        ?>

        <link rel="preload" as="style" href="<?php echo e($googleFontsUrl); ?>" />
        <link rel="stylesheet"         href="<?php echo e($googleFontsUrl); ?>" />

        <?php echo $__env->yieldPushContent('styles'); ?>

        <style>
            :root {
                --shop-logo-width:       <?php echo e(core()->getConfigData('general.design.shop_header.logo_width')          ?? 70); ?>px;
                --shop-header-height:    <?php echo e(core()->getConfigData('general.design.shop_header.header_height')        ?? 78); ?>px;
                --shop-body-font:        <?php echo e(core()->getConfigData('general.design.shop_fonts.body_size')             ?? 14); ?>px;
                --shop-nav-font:         <?php echo e(core()->getConfigData('general.design.shop_fonts.nav_size')              ?? 14); ?>px;
                --shop-heading-font:     <?php echo e(core()->getConfigData('general.design.shop_fonts.heading_size')          ?? 24); ?>px;
                --shop-card-h:           <?php echo e(core()->getConfigData('general.design.shop_products.card_image_height')  ?? 300); ?>px;
                --shop-card-w:           <?php echo e(core()->getConfigData('general.design.shop_products.card_image_width')   ?? 291); ?>px;
                --shop-body-family:      '<?php echo e($bodyFamily); ?>', sans-serif;
                --shop-heading-family:   '<?php echo e($headingFamily); ?>', serif;
                --shop-nav-family:       '<?php echo e($navFamily); ?>', sans-serif;
                --shop-body-weight:      <?php echo e($bodyWeight); ?>;
                --shop-heading-weight:   <?php echo e($headingWeight); ?>;
                --shop-nav-weight:       <?php echo e($navWeight); ?>;
            }
            .shop-logo-img                              { width: var(--shop-logo-width) !important; height: auto !important; }
            .shop-header-bar                            { min-height: var(--shop-header-height) !important; }
            html, body, body *                          { font-family: var(--shop-body-family) !important; font-size: var(--shop-body-font); font-weight: var(--shop-body-weight); }
            h1, h2, h3, h4, h5, h6,
            h1 *, h2 *, h3 *, h4 *, h5 *, h6 *        { font-family: var(--shop-heading-family) !important; font-size: var(--shop-heading-font); font-weight: var(--shop-heading-weight) !important; }
            nav, nav *, header nav, header nav *        { font-family: var(--shop-nav-family) !important; font-size: var(--shop-nav-font) !important; font-weight: var(--shop-nav-weight) !important; }
            .shop-card-img-wrap                         { max-height: var(--shop-card-h) !important; max-width: var(--shop-card-w) !important; }
        </style>

        <style>
            <?php echo core()->getConfigData('general.content.custom_scripts.custom_css'); ?>

        </style>

        <?php if(core()->getConfigData('general.content.speculation_rules.enabled')): ?>
            <script type="speculationrules">
                <?php echo json_encode(core()->getSpeculationRules(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE, 512) ?>
            </script>
        <?php endif; ?>

        <?php if(core()->getConfigData('general.content.facebook_pixel.enabled') && core()->getConfigData('general.content.facebook_pixel.pixel_id')): ?>
            <script>
                !function(f,b,e,v,n,t,s)
                {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                n.callMethod.apply(n,arguments):n.queue.push(arguments)};
                if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
                n.queue=[];t=b.createElement(e);t.async=!0;
                t.src=v;s=b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t,s)}(window, document,'script',
                'https://connect.facebook.net/en_US/fbevents.js');
                fbq('init', '<?php echo e(core()->getConfigData('general.content.facebook_pixel.pixel_id')); ?>');
                fbq('track', 'PageView');
            </script>
            <noscript>
                <img height="1" width="1" style="display:none"
                    src="https://www.facebook.com/tr?id=<?php echo e(core()->getConfigData('general.content.facebook_pixel.pixel_id')); ?>&ev=PageView&noscript=1"/>
            </noscript>
        <?php endif; ?>

        
        <?php if(core()->getConfigData('general.content.google_tag_manager.enabled') && core()->getConfigData('general.content.google_tag_manager.container_id')): ?>
            <?php $gtmId = core()->getConfigData('general.content.google_tag_manager.container_id'); ?>
            <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','<?php echo e($gtmId); ?>');</script>
        <?php endif; ?>

        
        <?php if(! core()->getConfigData('general.content.google_tag_manager.enabled') && core()->getConfigData('general.content.google_analytics.enabled') && core()->getConfigData('general.content.google_analytics.measurement_id')): ?>
            <?php $gaId = core()->getConfigData('general.content.google_analytics.measurement_id'); ?>
            <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo e($gaId); ?>"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', '<?php echo e($gaId); ?>');
            </script>
        <?php endif; ?>

        <?php echo view_render_event('bagisto.shop.layout.head.after'); ?>


    </head>

    <body>
        
        <?php if(core()->getConfigData('general.content.google_tag_manager.enabled') && core()->getConfigData('general.content.google_tag_manager.container_id')): ?>
            <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo e(core()->getConfigData('general.content.google_tag_manager.container_id')); ?>"
            height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <?php endif; ?>

        <?php echo view_render_event('bagisto.shop.layout.body.before'); ?>


        <a
            href="#main"
            class="skip-to-main-content-link"
        >
            Skip to main content
        </a>

        <!-- Built With Bagisto -->
        <div id="app">
            <!-- Flash Message Blade Component -->
            <?php if (isset($component)) { $__componentOriginal32b426cadde54f0d7b8de12fc0e5c6a0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal32b426cadde54f0d7b8de12fc0e5c6a0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.flash-group.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::flash-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal32b426cadde54f0d7b8de12fc0e5c6a0)): ?>
<?php $attributes = $__attributesOriginal32b426cadde54f0d7b8de12fc0e5c6a0; ?>
<?php unset($__attributesOriginal32b426cadde54f0d7b8de12fc0e5c6a0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal32b426cadde54f0d7b8de12fc0e5c6a0)): ?>
<?php $component = $__componentOriginal32b426cadde54f0d7b8de12fc0e5c6a0; ?>
<?php unset($__componentOriginal32b426cadde54f0d7b8de12fc0e5c6a0); ?>
<?php endif; ?>

            <!-- Confirm Modal Blade Component -->
            <?php if (isset($component)) { $__componentOriginal5692e00db184034e913f6f80572595d8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5692e00db184034e913f6f80572595d8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.modal.confirm','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::modal.confirm'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5692e00db184034e913f6f80572595d8)): ?>
<?php $attributes = $__attributesOriginal5692e00db184034e913f6f80572595d8; ?>
<?php unset($__attributesOriginal5692e00db184034e913f6f80572595d8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5692e00db184034e913f6f80572595d8)): ?>
<?php $component = $__componentOriginal5692e00db184034e913f6f80572595d8; ?>
<?php unset($__componentOriginal5692e00db184034e913f6f80572595d8); ?>
<?php endif; ?>

            <!-- Package modals (size guide, etc.) -->
            <?php echo $__env->yieldPushContent('modals'); ?>

            <!-- Page Header Blade Component -->
            <?php if($hasHeader): ?>
                <?php if (isset($component)) { $__componentOriginal8840dbae58ccad6ecf4eb407b99d7661 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8840dbae58ccad6ecf4eb407b99d7661 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.layouts.header.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::layouts.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8840dbae58ccad6ecf4eb407b99d7661)): ?>
<?php $attributes = $__attributesOriginal8840dbae58ccad6ecf4eb407b99d7661; ?>
<?php unset($__attributesOriginal8840dbae58ccad6ecf4eb407b99d7661); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8840dbae58ccad6ecf4eb407b99d7661)): ?>
<?php $component = $__componentOriginal8840dbae58ccad6ecf4eb407b99d7661; ?>
<?php unset($__componentOriginal8840dbae58ccad6ecf4eb407b99d7661); ?>
<?php endif; ?>
            <?php endif; ?>

            <?php if(
                core()->getConfigData('general.gdpr.settings.enabled')
                && core()->getConfigData('general.gdpr.cookie.enabled')
            ): ?>
                <?php if (isset($component)) { $__componentOriginalee22104ea56ac769eac5950f83fdb6cf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalee22104ea56ac769eac5950f83fdb6cf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.layouts.cookie.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::layouts.cookie'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalee22104ea56ac769eac5950f83fdb6cf)): ?>
<?php $attributes = $__attributesOriginalee22104ea56ac769eac5950f83fdb6cf; ?>
<?php unset($__attributesOriginalee22104ea56ac769eac5950f83fdb6cf); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalee22104ea56ac769eac5950f83fdb6cf)): ?>
<?php $component = $__componentOriginalee22104ea56ac769eac5950f83fdb6cf; ?>
<?php unset($__componentOriginalee22104ea56ac769eac5950f83fdb6cf); ?>
<?php endif; ?>
            <?php endif; ?>

            <?php echo view_render_event('bagisto.shop.layout.content.before'); ?>


            <!-- Page Content Blade Component -->
            <main id="main" class="bg-white">
                <?php echo e($slot); ?>

            </main>

            <?php echo view_render_event('bagisto.shop.layout.content.after'); ?>



            <!-- Page Services Blade Component -->
            <?php if($hasFeature): ?>
                <?php if (isset($component)) { $__componentOriginalc7f3325cf24ae738796526b5a914125a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7f3325cf24ae738796526b5a914125a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.layouts.services','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::layouts.services'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc7f3325cf24ae738796526b5a914125a)): ?>
<?php $attributes = $__attributesOriginalc7f3325cf24ae738796526b5a914125a; ?>
<?php unset($__attributesOriginalc7f3325cf24ae738796526b5a914125a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc7f3325cf24ae738796526b5a914125a)): ?>
<?php $component = $__componentOriginalc7f3325cf24ae738796526b5a914125a; ?>
<?php unset($__componentOriginalc7f3325cf24ae738796526b5a914125a); ?>
<?php endif; ?>
            <?php endif; ?>

            <!-- Page Footer Blade Component -->
            <?php if($hasFooter): ?>
                <?php if (isset($component)) { $__componentOriginal52eadd6893b05c6c47a48f4bafc8ff6b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal52eadd6893b05c6c47a48f4bafc8ff6b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.layouts.footer.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::layouts.footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal52eadd6893b05c6c47a48f4bafc8ff6b)): ?>
<?php $attributes = $__attributesOriginal52eadd6893b05c6c47a48f4bafc8ff6b; ?>
<?php unset($__attributesOriginal52eadd6893b05c6c47a48f4bafc8ff6b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal52eadd6893b05c6c47a48f4bafc8ff6b)): ?>
<?php $component = $__componentOriginal52eadd6893b05c6c47a48f4bafc8ff6b; ?>
<?php unset($__componentOriginal52eadd6893b05c6c47a48f4bafc8ff6b); ?>
<?php endif; ?>
            <?php endif; ?>
        </div>

        <?php echo view_render_event('bagisto.shop.layout.body.after'); ?>


        
        <?php if(core()->getConfigData('general.content.whatsapp_chat.enabled') && core()->getConfigData('general.content.whatsapp_chat.phone')): ?>
            <?php
                $waPhone  = preg_replace('/[^0-9]/', '', core()->getConfigData('general.content.whatsapp_chat.phone'));
                $waMsg    = urlencode(core()->getConfigData('general.content.whatsapp_chat.message') ?? 'Hello!');
                $waPos    = core()->getConfigData('general.content.whatsapp_chat.position') === 'left' ? 'left-5' : 'right-5';
            ?>
            <a href="https://wa.me/<?php echo e($waPhone); ?>?text=<?php echo e($waMsg); ?>"
               target="_blank" rel="noopener"
               class="fixed bottom-6 <?php echo e($waPos); ?> z-50 flex h-14 w-14 items-center justify-center rounded-full bg-[#25D366] shadow-lg hover:scale-110 transition-transform"
               title="Chat on WhatsApp">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" class="h-7 w-7">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
            </a>
        <?php endif; ?>

        <?php echo $__env->yieldPushContent('scripts'); ?>

        
        <?php if(config('push-notification.vapid_public_key')): ?>
        <script>
        (function() {
            if (!('serviceWorker' in navigator) || !('PushManager' in window)) return;
            navigator.serviceWorker.register('/sw.js').then(function(reg) {
                return reg.pushManager.getSubscription().then(function(sub) {
                    if (sub) return;
                    fetch('/push/vapid-key').then(r => r.json()).then(function(d) {
                        if (!d.key) return;
                        const appServerKey = urlBase64ToUint8Array(d.key);
                        reg.pushManager.subscribe({ userVisibleOnly: true, applicationServerKey: appServerKey })
                            .then(function(subscription) {
                                const keys = subscription.toJSON().keys || {};
                                fetch('/push/subscribe', {
                                    method: 'POST',
                                    headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'},
                                    body: JSON.stringify({ endpoint: subscription.endpoint, p256dh: keys.p256dh, auth: keys.auth })
                                });
                            }).catch(() => {});
                    });
                });
            }).catch(() => {});

            function urlBase64ToUint8Array(base64String) {
                const padding = '='.repeat((4 - base64String.length % 4) % 4);
                const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
                const rawData = window.atob(base64);
                return new Uint8Array([...rawData].map(c => c.charCodeAt(0)));
            }
        })();
        </script>
        <?php endif; ?>

        <?php if(core()->getConfigData('general.design.exit_intent.enabled')): ?>
            <?php
                $eiTitle   = core()->getConfigData('general.design.exit_intent.title')       ?? 'Wait! Before you go...';
                $eiSub     = core()->getConfigData('general.design.exit_intent.subtitle')     ?? 'Get 10% off your first order';
                $eiCoupon  = core()->getConfigData('general.design.exit_intent.coupon_code')  ?? 'STAYWITHUS';
                $eiBtn     = core()->getConfigData('general.design.exit_intent.button_text')  ?? 'Claim My Discount';
            ?>

            
            <div id="exit-popup" class="pointer-events-none fixed inset-0 z-[200] flex items-center justify-center bg-black/50 opacity-0 transition-opacity duration-300">
                <div class="relative mx-4 w-full max-w-md rounded-2xl bg-white p-8 shadow-2xl dark:bg-gray-900">
                    <button
                        onclick="closeExitPopup()"
                        class="absolute right-4 top-4 text-2xl text-gray-400 hover:text-gray-600"
                        aria-label="Close"
                    >&times;</button>

                    <div class="text-center">
                        <p class="text-3xl">🎁</p>
                        <h2 class="mt-3 text-2xl font-bold text-gray-900 dark:text-white"><?php echo e($eiTitle); ?></h2>
                        <p class="mt-2 text-gray-500 dark:text-gray-400"><?php echo e($eiSub); ?></p>

                        <div class="my-5 rounded-xl border-2 border-dashed border-blue-400 bg-blue-50 py-3 dark:bg-blue-900/20">
                            <p class="font-mono text-2xl font-bold tracking-widest text-blue-700 dark:text-blue-300"><?php echo e($eiCoupon); ?></p>
                        </div>

                        <button
                            onclick="navigator.clipboard?.writeText('<?php echo e($eiCoupon); ?>'); closeExitPopup();"
                            class="primary-button w-full justify-center"
                        >
                            <?php echo e($eiBtn); ?>

                        </button>

                        <button onclick="closeExitPopup()" class="mt-3 block w-full text-sm text-gray-400 hover:text-gray-600">
                            No thanks, I'll pay full price
                        </button>
                    </div>
                </div>
            </div>

            <script>
                (function () {
                    const popup  = document.getElementById('exit-popup');
                    const shown  = sessionStorage.getItem('exit_popup_shown');
                    if (shown) return;

                    document.addEventListener('mouseleave', function handler(e) {
                        if (e.clientY > 10) return;
                        document.removeEventListener('mouseleave', handler);
                        sessionStorage.setItem('exit_popup_shown', '1');
                        popup.classList.remove('pointer-events-none', 'opacity-0');
                        popup.classList.add('pointer-events-auto', 'opacity-100');
                    });
                })();

                function closeExitPopup() {
                    const popup = document.getElementById('exit-popup');
                    popup.classList.add('pointer-events-none', 'opacity-0');
                    popup.classList.remove('pointer-events-auto', 'opacity-100');
                }
            </script>
        <?php endif; ?>

        <?php echo view_render_event('bagisto.shop.layout.vue-app-mount.before'); ?>

        <script>
            /**
             * Load event, the purpose of using the event is to mount the application
             * after all of our `Vue` components which is present in blade file have
             * been registered in the app. No matter what `app.mount()` should be
             * called in the last.
             */
            window.addEventListener("load", function (event) {
                app.mount("#app");
            });
        </script>

        <?php echo view_render_event('bagisto.shop.layout.vue-app-mount.after'); ?>


        <script type="text/javascript">
            <?php echo core()->getConfigData('general.content.custom_scripts.custom_javascript'); ?>

        </script>

        <?php if(core()->getConfigData('general.content.facebook_pixel.enabled') && core()->getConfigData('general.content.facebook_pixel.pixel_id')): ?>
            <script>
                (function () {
                    if (typeof fbq === 'undefined') return;

                    var _fetch = window.fetch;
                    window.fetch = function () {
                        var args = arguments;
                        var url  = typeof args[0] === 'string' ? args[0] : (args[0] instanceof Request ? args[0].url : '');

                        return _fetch.apply(window, args).then(function (response) {
                            if (/\/cart\/add/.test(url)) {
                                response.clone().json().then(function (data) {
                                    if (data && data.message === 'success') {
                                        fbq('track', 'AddToCart');
                                    }
                                }).catch(function () {});
                            }
                            return response;
                        });
                    };
                })();
            </script>
        <?php endif; ?>

        <?php echo $__env->make('ai-support::shop.chat-widget', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        
        <?php if(core()->getConfigData('general.content.tiktok_pixel.enabled') && core()->getConfigData('general.content.tiktok_pixel.pixel_id')): ?>
            <?php $ttPixelId = core()->getConfigData('general.content.tiktok_pixel.pixel_id'); ?>
            <script>
                !function(w, d, t) {
                    w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var i="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=i,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};var o=document.createElement("script");o.type="text/javascript",o.async=!0,o.src=i+"?sdkid="+e+"&lib="+t;var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(o,a)};
                    ttq.load('<?php echo e($ttPixelId); ?>');
                    ttq.page();
                }(window, document, 'ttq');
            </script>
        <?php endif; ?>

        
        <?php if(core()->getConfigData('general.content.tawk_chat.enabled') && core()->getConfigData('general.content.tawk_chat.property_id')): ?>
            <?php
                $tawkProp   = core()->getConfigData('general.content.tawk_chat.property_id');
                $tawkWidget = core()->getConfigData('general.content.tawk_chat.widget_id') ?? '1li0';
            ?>
            <script type="text/javascript">
                var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
                (function(){
                    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
                    s1.async=true;
                    s1.src='https://embed.tawk.to/<?php echo e($tawkProp); ?>/<?php echo e($tawkWidget); ?>';
                    s1.charset='UTF-8';
                    s1.setAttribute('crossorigin','*');
                    s0.parentNode.insertBefore(s1,s0);
                })();
            </script>
        <?php endif; ?>

        
        <?php if(core()->getConfigData('general.design.whatsapp_button.enabled') && core()->getConfigData('general.design.whatsapp_button.phone')): ?>
            <?php
                $waPhone = preg_replace('/[^0-9]/', '', core()->getConfigData('general.design.whatsapp_button.phone'));
                $waMsg   = urlencode(core()->getConfigData('general.design.whatsapp_button.message') ?? 'Hello, I need help with my order.');
            ?>

            <a
                href="https://wa.me/<?php echo e($waPhone); ?>?text=<?php echo e($waMsg); ?>"
                target="_blank"
                rel="noopener"
                class="fixed bottom-24 right-5 z-[120] flex h-14 w-14 items-center justify-center rounded-full bg-[#25D366] shadow-xl transition-transform hover:scale-110"
                aria-label="Chat on WhatsApp"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" class="h-7 w-7">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
            </a>
        <?php endif; ?>

        
        <?php $compareCount = count(session('comparison_products', [])); ?>
        <div
            id="compare-floating-bar"
            class="<?php echo e($compareCount > 0 ? '' : 'hidden'); ?> fixed bottom-0 left-0 right-0 z-[100] flex items-center justify-center gap-4 bg-navyBlue py-2 text-sm text-white shadow-lg"
        >
            <span><span id="compare-count"><?php echo e($compareCount); ?></span> product(s) selected</span>
            <a href="<?php echo e(route('shop.comparison.index')); ?>" class="rounded bg-white px-4 py-1 font-medium text-navyBlue hover:bg-gray-100">
                Compare Now
            </a>
            <form action="<?php echo e(route('shop.comparison.clear')); ?>" method="POST" class="inline">
                <?php echo csrf_field(); ?>
                <button type="submit" class="text-xs text-white/70 hover:underline">Clear</button>
            </form>
        </div>

        <script>
            function addToCompareSession(productId) {
                fetch('<?php echo e(route('shop.comparison.add')); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ product_id: productId })
                }).then(r => r.json()).then(d => {
                    if (d.success) {
                        const bar = document.getElementById('compare-floating-bar');
                        const count = document.getElementById('compare-count');
                        if (bar) bar.classList.remove('hidden');
                        if (count) count.textContent = d.count;
                    }
                });
            }
        </script>

        
        <?php if(core()->getConfigData('general.design.newsletter_popup.enabled')): ?>
            <?php
                $npDelay = (int)(core()->getConfigData('general.design.newsletter_popup.delay') ?? 5);
                $npTitle = core()->getConfigData('general.design.newsletter_popup.title')       ?? 'Get 10% Off Your First Order';
                $npSub   = core()->getConfigData('general.design.newsletter_popup.subtitle')    ?? 'Subscribe for exclusive deals and early access.';
                $npBtn   = core()->getConfigData('general.design.newsletter_popup.button_text') ?? 'Get My Discount';
            ?>

            <div
                id="nl-popup"
                class="pointer-events-none fixed inset-0 z-[190] flex items-center justify-center bg-black/50 opacity-0 transition-opacity duration-300"
            >
                <div class="relative mx-4 w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl">
                    <button
                        onclick="closeNlPopup()"
                        class="absolute right-4 top-4 text-2xl text-gray-400 hover:text-gray-600"
                        aria-label="Close"
                    >&times;</button>

                    <div class="bg-navyBlue px-8 pb-6 pt-8 text-center text-white">
                        <p class="text-3xl">✉️</p>
                        <h2 class="mt-3 text-2xl font-bold"><?php echo e($npTitle); ?></h2>
                        <p class="mt-2 text-sm text-white/80"><?php echo e($npSub); ?></p>
                    </div>

                    <div class="px-8 py-6">
                        <form id="nl-form" onsubmit="submitNlForm(event)">
                            <input
                                id="nl-email"
                                type="email"
                                required
                                placeholder="Your email address"
                                class="w-full rounded-lg border border-gray-200 px-4 py-3 text-sm outline-none focus:border-navyBlue focus:ring-1 focus:ring-navyBlue"
                            />
                            <button
                                type="submit"
                                class="primary-button mt-3 w-full justify-center"
                            ><?php echo e($npBtn); ?></button>
                        </form>
                        <p id="nl-success" class="hidden mt-3 text-center text-sm font-medium text-green-600">
                            🎉 You're subscribed! Check your inbox.
                        </p>
                        <button onclick="closeNlPopup()" class="mt-3 block w-full text-center text-xs text-gray-400 hover:text-gray-600">
                            No thanks
                        </button>
                    </div>
                </div>
            </div>

            <script>
                (function () {
                    if (localStorage.getItem('aven_nl_subscribed') || sessionStorage.getItem('aven_nl_shown')) return;
                    setTimeout(function () {
                        var popup = document.getElementById('nl-popup');
                        if (!popup) return;
                        sessionStorage.setItem('aven_nl_shown', '1');
                        popup.classList.remove('pointer-events-none', 'opacity-0');
                        popup.classList.add('pointer-events-auto', 'opacity-100');
                    }, <?php echo e($npDelay * 1000); ?>);
                })();

                function closeNlPopup() {
                    var popup = document.getElementById('nl-popup');
                    popup.classList.add('pointer-events-none', 'opacity-0');
                    popup.classList.remove('pointer-events-auto', 'opacity-100');
                }

                function submitNlForm(e) {
                    e.preventDefault();
                    var email = document.getElementById('nl-email').value;
                    fetch('<?php echo e(route('shop.subscription.store')); ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        },
                        body: JSON.stringify({ email: email }),
                    })
                    .then(function () {
                        localStorage.setItem('aven_nl_subscribed', '1');
                        document.getElementById('nl-form').classList.add('hidden');
                        document.getElementById('nl-success').classList.remove('hidden');
                        setTimeout(closeNlPopup, 2000);
                    })
                    .catch(function () { closeNlPopup(); });
                }
            </script>
        <?php endif; ?>
    </body>
</html>
<?php /**PATH D:\aven\packages\Webkul\Shop\src/resources/views/components/layouts/index.blade.php ENDPATH**/ ?>