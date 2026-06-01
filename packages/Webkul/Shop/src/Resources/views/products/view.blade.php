@inject ('reviewHelper', 'Webkul\Product\Helpers\Review')
@inject ('productViewHelper', 'Webkul\Product\Helpers\View')

@php
    $avgRatings = $reviewHelper->getAverageRating($product);

    $percentageRatings = $reviewHelper->getPercentageRating($product);

    $customAttributeValues = $productViewHelper->getAdditionalData($product);

    $attributeData = collect($customAttributeValues)->filter(fn ($item) => ! empty($item['value']));
@endphp

@php
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
@endphp

@if (core()->getConfigData('general.content.google_analytics.enabled') && core()->getConfigData('general.content.google_analytics.measurement_id'))
@push('scripts')
<script>
    window.addEventListener('load', function () {
        if (typeof gtag === 'undefined') return;
        gtag('event', 'view_item', {
            currency: '{{ core()->getCurrentCurrencyCode() }}',
            value:    {{ (float) ($product->getTypeInstance()->getMinimalPrice() ?? 0) }},
            items: [{
                item_id:   '{{ $product->sku }}',
                item_name: @json($product->name),
                price:     {{ (float) ($product->getTypeInstance()->getMinimalPrice() ?? 0) }},
                quantity:  1,
            }],
        });
    });
</script>
@endpush
@endif

<!-- SEO Meta Content -->
@push('meta')
    <meta name="description" content="{{ trim($product->meta_description) != "" ? $product->meta_description : \Illuminate\Support\Str::limit(strip_tags($product->description), 120, '') }}"/>

    <meta name="keywords" content="{{ $product->meta_keywords }}"/>

    @if (core()->getConfigData('catalog.rich_snippets.products.enable') !== '0')
        <script type="application/ld+json">
            {!! app('Webkul\Product\Helpers\SEO')->getProductJsonLd($product) !!}
        </script>
    @endif

    <?php $productBaseImage = product_image()->getProductBaseImage($product); ?>

    <meta name="twitter:card" content="summary_large_image" />

    <meta name="twitter:title" content="{{ $product->name }}" />

    <meta name="twitter:description" content="{!! htmlspecialchars(trim(strip_tags($product->description))) !!}" />

    <meta name="twitter:image:alt" content="" />

    <meta name="twitter:image" content="{{ $productBaseImage['medium_image_url'] }}" />

    <meta property="og:type" content="og:product" />

    <meta property="og:title" content="{{ $product->name }}" />

    <meta property="og:image" content="{{ $productBaseImage['medium_image_url'] }}" />

    <meta property="og:description" content="{!! htmlspecialchars(trim(strip_tags($product->description))) !!}" />

    <meta property="og:url" content="{{ route('shop.product_or_category.index', $product->url_key) }}" />
@endPush

@if (core()->getConfigData('general.content.facebook_pixel.enabled') && core()->getConfigData('general.content.facebook_pixel.pixel_id'))
    @push('scripts')
        <script>
            window.addEventListener('load', function () {
                if (typeof fbq === 'undefined') return;
                fbq('track', 'ViewContent', {
                    content_ids:  ['{{ $product->sku }}'],
                    content_name: @json($product->name),
                    content_type: 'product',
                    value:        {{ (float) $product->getTypeInstance()->getMinimalPrice() }},
                    currency:     '{{ core()->getCurrentCurrencyCode() }}',
                });
            });
        </script>
    @endpush
@endif

<!-- Page Layout -->
<x-shop::layouts>
    <!-- Page Title -->
    <x-slot:title>
        {{ trim($product->meta_title) != "" ? $product->meta_title : $product->name }}
    </x-slot>

    {!! view_render_event('bagisto.shop.products.view.before', ['product' => $product]) !!}

    <!-- Breadcrumbs -->
    @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
        <div class="flex justify-center px-7 max-lg:hidden">
            <x-shop::breadcrumbs
                name="product"
                :entity="$product"
            />
        </div>
    @endif

    <!-- Product Information Vue Component -->
    <v-product>
        <x-shop::shimmer.products.view />
    </v-product>

    <!-- Information Section -->
    <div class="1180:mt-20">
        <div class="max-1180:hidden">
            <x-shop::tabs
                position="center"
                ref="productTabs"
            >
                <!-- Description Tab -->
                {!! view_render_event('bagisto.shop.products.view.description.before', ['product' => $product]) !!}

                <x-shop::tabs.item
                    id="descritpion-tab"
                    class="container mt-[60px] !p-0"
                    :title="trans('shop::app.products.view.description')"
                    :is-selected="true"
                >
                    <div class="container mt-[60px] max-1180:px-5">
                        <p class="text-lg text-zinc-500 max-1180:text-sm">
                            {!! $product->description !!}
                        </p>
                    </div>
                </x-shop::tabs.item>

                {!! view_render_event('bagisto.shop.products.view.description.after', ['product' => $product]) !!}

                <!-- Additional Information Tab -->
                @if(count($attributeData))
                    <x-shop::tabs.item
                        id="information-tab"
                        class="container mt-[60px] !p-0"
                        :title="trans('shop::app.products.view.additional-information')"
                        :is-selected="false"
                    >
                        <div class="container mt-[60px] max-1180:px-5">
                            <div class="mt-8 grid max-w-max grid-cols-[auto_1fr] gap-4">
                                @foreach ($customAttributeValues as $customAttributeValue)
                                    @if (! empty($customAttributeValue['value']))
                                        <div class="grid">
                                            <p class="text-base text-black">
                                                {!! $customAttributeValue['label'] !!}
                                            </p>
                                        </div>

                                        @if ($customAttributeValue['type'] == 'file')
                                            <a
                                                href="{{ Storage::url($product[$customAttributeValue['code']]) }}"
                                                download="{{ $customAttributeValue['label'] }}"
                                            >
                                                <span class="icon-download text-2xl"></span>
                                            </a>
                                        @elseif ($customAttributeValue['type'] == 'image')
                                            <a
                                                href="{{ Storage::url($product[$customAttributeValue['code']]) }}"
                                                download="{{ $customAttributeValue['label'] }}"
                                            >
                                                <img
                                                    class="min-h-5 min-w-5 h-5 w-5"
                                                    src="{{ Storage::url($customAttributeValue['value']) }}"
                                                />
                                            </a>
                                        @else
                                            <div class="grid">
                                                <p class="text-base text-zinc-500">
                                                    {!! $customAttributeValue['value'] !!}
                                                </p>
                                            </div>
                                        @endif
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </x-shop::tabs.item>
                @endif

                <!-- Reviews Tab -->
                <x-shop::tabs.item
                    id="review-tab"
                    class="container mt-[60px] !p-0"
                    :title="trans('shop::app.products.view.review')"
                    :is-selected="false"
                >
                    @include('shop::products.view.reviews')
                </x-shop::tabs.item>
            </x-shop::tabs>
        </div>

        <!-- Product Q&A -->
        @includeIf('product_qa::shop.widget', ['productId' => $product->id])
    </div>

    <!-- Information Section -->
    <div class="container mt-6 grid gap-3 !p-0 max-1180:px-5 1180:hidden">
        <!-- Description Accordion -->
        <x-shop::accordion
            class="max-md:border-none"
            :is-active="true"
        >
            <x-slot:header class="bg-gray-100 max-md:!py-3 max-sm:!py-2">
                <p class="text-base font-medium 1180:hidden">
                    @lang('shop::app.products.view.description')
                </p>
            </x-slot>

            <x-slot:content class="max-sm:px-0">
                <div class="mb-5 text-lg text-zinc-500 max-1180:text-sm max-md:mb-1 max-md:px-4">
                    {!! $product->description !!}
                </div>
            </x-slot>
        </x-shop::accordion>

        <!-- Additional Information Accordion -->
        @if (count($attributeData))
            <x-shop::accordion
                class="max-md:border-none"
                :is-active="false"
            >
                <x-slot:header class="bg-gray-100 max-md:!py-3 max-sm:!py-2">
                    <p class="text-base font-medium 1180:hidden">
                        @lang('shop::app.products.view.additional-information')
                    </p>
                </x-slot>

                <x-slot:content class="max-sm:px-0">
                    <div class="container max-1180:px-5">
                        <div class="grid max-w-max grid-cols-[auto_1fr] gap-4 text-lg text-zinc-500 max-1180:text-sm">
                            @foreach ($customAttributeValues as $customAttributeValue)
                                @if (! empty($customAttributeValue['value']))
                                    <div class="grid">
                                        <p
                                            class="text-base text-black"
                                            v-pre
                                        >
                                            {{ $customAttributeValue['label'] }}
                                        </p>
                                    </div>

                                    @if ($customAttributeValue['type'] == 'file')
                                        <a
                                            href="{{ Storage::url($product[$customAttributeValue['code']]) }}"
                                            download="{{ $customAttributeValue['label'] }}"
                                        >
                                            <span class="icon-download text-2xl"></span>
                                        </a>
                                    @elseif ($customAttributeValue['type'] == 'image')
                                        <a
                                            href="{{ Storage::url($product[$customAttributeValue['code']]) }}"
                                            download="{{ $customAttributeValue['label'] }}"
                                        >
                                            <img
                                                class="min-h-5 min-w-5 h-5 w-5"
                                                src="{{ Storage::url($customAttributeValue['value']) }}"
                                                alt="Product Image"
                                            />
                                        </a>
                                    @else
                                        <div class="grid">
                                            <p
                                                class="text-base text-zinc-500"
                                                v-pre
                                            >
                                                {{ $customAttributeValue['value'] ?? '-' }}
                                            </p>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    </div>
                </x-slot>
            </x-shop::accordion>
        @endif

        <!-- Reviews Accordion -->
        <x-shop::accordion
            class="max-md:border-none"
            :is-active="false"
        >
            <x-slot:header
                class="bg-gray-100 max-md:!py-3 max-sm:!py-2"
                id="review-accordian-button"
            >
                <p class="text-base font-medium">
                    @lang('shop::app.products.view.review')
                </p>
            </x-slot>

            <x-slot:content>
                @include('shop::products.view.reviews')
            </x-slot>
        </x-shop::accordion>
    </div>

    {{-- Frequently Bought Together --}}
    @php $relatedProducts = $product->related_products()->whereHas('product_flats', fn ($q) => $q->where('status', 1))->limit(3)->get(); @endphp

    @if ($relatedProducts->isNotEmpty())
        <div class="container mt-12 px-[60px] max-1180:px-5 max-sm:px-4">
            <h2 class="mb-6 text-2xl font-medium max-sm:text-lg">
                @lang('shop::app.products.view.frequently-bought-together')
            </h2>

            <div class="flex flex-wrap items-center gap-4">
                {{-- Current product --}}
                <div class="flex w-36 flex-col items-center text-center max-sm:w-28">
                    <div class="overflow-hidden rounded-xl bg-gray-50">
                        <img src="{{ $product->base_image->small_image_url ?? '' }}" class="aspect-square w-full object-cover" alt="{{ $product->name }}">
                    </div>
                    <p class="mt-2 line-clamp-2 text-xs font-medium text-gray-700">{{ $product->name }}</p>
                    <p class="mt-0.5 text-xs text-gray-500">{!! $product->getTypeInstance()->getPriceHtml() !!}</p>
                </div>

                @foreach ($relatedProducts as $related)
                    <span class="text-2xl font-light text-gray-400">+</span>

                    <a href="{{ route('shop.product_or_category.index', $related->url_key) }}" class="flex w-36 flex-col items-center text-center max-sm:w-28 group">
                        <div class="overflow-hidden rounded-xl bg-gray-50">
                            <img src="{{ $related->base_image->small_image_url ?? '' }}" class="aspect-square w-full object-cover transition-transform duration-300 group-hover:scale-105" alt="{{ $related->name }}">
                        </div>
                        <p class="mt-2 line-clamp-2 text-xs font-medium text-gray-700">{{ $related->name }}</p>
                        <p class="mt-0.5 text-xs text-gray-500">{!! $related->getTypeInstance()->getPriceHtml() !!}</p>
                    </a>
                @endforeach

                <div class="ml-2 flex flex-col items-start gap-2 max-sm:w-full max-sm:ml-0">
                    <a
                        href="{{ route('shop.checkout.onepage.index') }}"
                        class="primary-button whitespace-nowrap"
                    >
                        @lang('shop::app.products.view.buy-all-together')
                    </a>
                    <p class="text-xs text-gray-400">
                        @lang('shop::app.products.view.add-each-separately')
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Recently Viewed -->
    <v-recently-viewed></v-recently-viewed>

    <v-product-associations></v-product-associations>

    {!! view_render_event('bagisto.shop.products.view.after', ['product' => $product]) !!}

    @push('scripts')
        @if (core()->getConfigData('general.design.countdown_timer.enabled'))
        <script>
            (function () {
                const key   = 'aven_ct';
                const hours = {{ (int)(core()->getConfigData('general.design.countdown_timer.hours') ?? 6) }};
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
        @endif

        {{-- Viewing Now Counter --}}
        <script>
            (function () {
                const el = document.getElementById('viewing-count');
                if (!el) return;
                const base = {{ 8 + ($product->id % 17) }};
                const update = () => {
                    const delta = Math.floor(Math.random() * 5) - 2;
                    const count = Math.max(6, Math.min(32, base + delta));
                    el.textContent = count;
                };
                update();
                setInterval(update, 30000);
            })();
        </script>

        {{-- Flash Sale Countdown --}}
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

        {{-- Back in Stock Notify --}}
        <script>
            function submitStockNotify(e, productId) {
                e.preventDefault();
                var form  = e.target;
                var email = form.querySelector('[name="email"]').value;
                var msg   = document.getElementById('stock-notify-msg');

                fetch('{{ route('shop.stock_notification.store') }}', {
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

        {{-- Save current product to Recently Viewed in localStorage --}}
        <script>
            (function () {
                const key = 'aven_rv';
                const current = {
                    id:    {{ $product->id }},
                    name:  @json($product->name),
                    url:   @json(route('shop.product_or_category.index', $product->url_key)),
                    image: @json($productBaseImage['small_image_url'] ?? ''),
                    price: @json(core()->currency($product->getTypeInstance()->getMinimalPrice())),
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
                    @lang('shop::app.products.view.recently-viewed')
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
                        <p class="mt-2 line-clamp-2 text-sm font-medium text-gray-800">@{{ product.name }}</p>
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
                        this.products = rv.filter(p => p && p.id && p.id !== {{ $product->id }});
                    } catch (e) {}
                },
            });
        </script>
    @endpush

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-product-template"
        >
            <x-shop::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form
                    ref="formData"
                    @submit="handleSubmit($event, addToCart)"
                >
                    <input
                        type="hidden"
                        name="product_id"
                        value="{{ $product->id }}"
                    >

                    <input
                        type="hidden"
                        name="is_buy_now"
                        v-model="is_buy_now"
                    >

                    <div class="container px-[60px] max-1180:px-0">
                        <div class="mt-12 flex gap-9 max-1180:flex-wrap max-lg:mt-0 max-sm:gap-y-4">
                            <!-- Gallery Blade Inclusion -->
                            @include('shop::products.view.gallery')

                            <!-- Details -->
                            <div class="relative max-w-[590px] max-1180:w-full max-1180:max-w-full max-1180:px-5 max-sm:px-4">
                                {!! view_render_event('bagisto.shop.products.name.before', ['product' => $product]) !!}

                                <div class="flex justify-between gap-4">
                                    <h1 class="break-words text-3xl font-medium max-sm:text-xl" v-pre>
                                        {{ $product->name }}
                                    </h1>

                                    @if (core()->getConfigData('customer.settings.wishlist.wishlist_option'))
                                        <div
                                            class="max-sm:min-h-7 max-sm:min-w-7 flex max-h-[46px] min-h-[46px] min-w-[46px] cursor-pointer items-center justify-center rounded-full border bg-white text-2xl transition-all hover:opacity-[0.8] max-sm:max-h-7 max-sm:text-base"
                                            role="button"
                                            aria-label="@lang('shop::app.products.view.add-to-wishlist')"
                                            tabindex="0"
                                            :class="isWishlist ? 'icon-heart-fill text-red-600' : 'icon-heart'"
                                            @click="addToWishlist"
                                        >
                                        </div>
                                    @endif
                                </div>

                                {!! view_render_event('bagisto.shop.products.name.after', ['product' => $product]) !!}

                                <!-- Rating -->
                                {!! view_render_event('bagisto.shop.products.rating.before', ['product' => $product]) !!}

                                @if ($totalRatings = $reviewHelper->getTotalFeedback($product))
                                    <!-- Scroll To Reviews Section and Activate Reviews Tab -->
                                    <div
                                        class="mt-1 w-max cursor-pointer max-sm:mt-1.5"
                                        role="button"
                                        tabindex="0"
                                        @click="scrollToReview"
                                    >
                                        <x-shop::products.ratings
                                            class="transition-all hover:border-gray-400 max-sm:px-3 max-sm:py-1"
                                            :average="$avgRatings"
                                            :total="$totalRatings"
                                            ::rating="true"
                                        />
                                    </div>
                                @endif

                                {!! view_render_event('bagisto.shop.products.rating.after', ['product' => $product]) !!}

                                {{-- Social Proof --}}
                                @php
                                    $soldCount = \Illuminate\Support\Facades\DB::table('order_items')
                                        ->join('orders', 'orders.id', '=', 'order_items.order_id')
                                        ->where('order_items.product_id', $product->id)
                                        ->where('orders.created_at', '>=', now()->subHours(24))
                                        ->whereIn('orders.status', ['processing', 'completed', 'pending', 'complete'])
                                        ->sum('order_items.qty_ordered');
                                @endphp

                                <div class="mt-2.5 flex flex-wrap items-center gap-x-4 gap-y-1.5 text-xs">
                                    @if ($soldCount > 0)
                                        <span class="flex items-center gap-1.5 font-medium text-orange-600">
                                            <span class="h-1.5 w-1.5 rounded-full bg-orange-500"></span>
                                            @lang('shop::app.products.view.sold-today', ['count' => $soldCount])
                                        </span>
                                    @endif

                                    <span
                                        class="flex items-center gap-1.5 font-medium text-green-600"
                                        id="viewing-now-badge"
                                    >
                                        <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-green-500"></span>
                                        <span id="viewing-count">--</span>
                                        @lang('shop::app.products.view.viewing-now')
                                    </span>
                                </div>

                                <!-- Pricing -->
                                {!! view_render_event('bagisto.shop.products.price.before', ['product' => $product]) !!}

                                @php
                                    $flashSale = app(\Webkul\FlashSale\Services\FlashSaleService::class)
                                        ->getActiveForProduct($product->id);
                                @endphp

                                @if ($flashSale)
                                    <div
                                        class="mt-3 flex items-center gap-2 rounded-xl bg-red-50 border border-red-200 px-4 py-2"
                                        x-data="flashCountdown('{{ $flashSale->ends_at->toIso8601String() }}')"
                                        x-init="init()"
                                    >
                                        <span class="text-red-600 font-bold text-sm">⚡ Flash Sale ends in:</span>
                                        <span class="font-mono font-bold text-red-600 text-sm" x-text="display">--:--:--</span>
                                    </div>
                                @endif

                                <p class="mt-[22px] flex items-center gap-2.5 text-2xl !font-medium max-sm:mt-2 max-sm:gap-x-2.5 max-sm:gap-y-0 max-sm:text-lg">
                                    {!! $product->getTypeInstance()->getPriceHtml() !!}
                                </p>

                                @if (\Webkul\Tax\Facades\Tax::isInclusiveTaxProductPrices())
                                    <span class="text-sm font-normal text-zinc-500 max-sm:text-xs">
                                        (@lang('shop::app.products.view.tax-inclusive'))
                                    </span>
                                @endif

                                @if (count($product->getTypeInstance()->getCustomerGroupPricingOffers()))
                                    <div class="mt-2.5 grid gap-1.5">
                                        @foreach ($product->getTypeInstance()->getCustomerGroupPricingOffers() as $offer)
                                            <p class="text-zinc-500 [&>*]:text-black">
                                                {!! $offer !!}
                                            </p>
                                        @endforeach
                                    </div>
                                @endif

                                {!! view_render_event('bagisto.shop.products.price.after', ['product' => $product]) !!}

                                {{-- Low Stock Counter --}}
                                @php $stockQty = $product->totalQuantity(); @endphp

                                @if ($stockQty > 0 && $stockQty <= 10)
                                    <div class="mt-3">
                                        @if ($stockQty <= 3)
                                            <span class="inline-flex items-center gap-2 rounded-full bg-red-50 px-3 py-1 text-sm font-medium text-red-600 dark:bg-red-900/20">
                                                <span class="h-2 w-2 animate-pulse rounded-full bg-red-500"></span>
                                                @lang('shop::app.products.view.only-left', ['qty' => $stockQty]) — @lang('shop::app.products.view.order-soon')
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-2 rounded-full bg-orange-50 px-3 py-1 text-sm font-medium text-orange-600 dark:bg-orange-900/20">
                                                <span class="h-2 w-2 rounded-full bg-orange-400"></span>
                                                @lang('shop::app.products.view.only-left', ['qty' => $stockQty])
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                {!! view_render_event('bagisto.shop.products.short_description.before', ['product' => $product]) !!}

                                <p class="mt-6 text-lg text-zinc-500 max-sm:mt-1.5 max-sm:text-sm">
                                    {!! $product->short_description !!}
                                </p>

                                {!! view_render_event('bagisto.shop.products.short_description.after', ['product' => $product]) !!}

                                @include('shop::products.view.types.simple')

                                @include('shop::products.view.types.configurable')

                                @include('shop::products.view.types.grouped')

                                @include('shop::products.view.types.bundle')

                                @include('shop::products.view.types.downloadable')

                                @include('shop::products.view.types.booking')

                                <!-- Size Guide -->
                                @include('size-guide::shop.size-guide-modal')

                                {{-- Countdown Timer --}}
                                @if (core()->getConfigData('general.design.countdown_timer.enabled'))
                                    @php
                                        $ctMessage = core()->getConfigData('general.design.countdown_timer.message') ?? 'Limited time offer! Ends in:';
                                        $ctHours   = (int)(core()->getConfigData('general.design.countdown_timer.hours') ?? 6);
                                    @endphp
                                    <div class="mt-4 flex items-center gap-3 rounded-xl border border-red-100 bg-red-50 px-4 py-2.5 max-sm:flex-wrap dark:bg-red-900/20">
                                        <span class="text-sm font-medium text-red-700 dark:text-red-400">{{ $ctMessage }}</span>
                                        <div class="flex items-center gap-1 font-mono text-base font-bold text-red-700 dark:text-red-400">
                                            <span id="ct-h" class="rounded bg-red-100 px-1.5 py-0.5">00</span>
                                            <span>:</span>
                                            <span id="ct-m" class="rounded bg-red-100 px-1.5 py-0.5">00</span>
                                            <span>:</span>
                                            <span id="ct-s" class="rounded bg-red-100 px-1.5 py-0.5">00</span>
                                        </div>
                                    </div>
                                @endif

                                <!-- Product Actions and Quantity Box -->
                                <div id="atc-trigger" class="mt-8 flex max-w-[470px] gap-4 max-sm:mt-4">

                                    {!! view_render_event('bagisto.shop.products.view.quantity.before', ['product' => $product]) !!}

                                    @if ($product->getTypeInstance()->showQuantityBox())
                                        <x-shop::quantity-changer
                                            name="quantity"
                                            value="1"
                                            class="gap-x-4 rounded-xl px-7 py-4 max-md:py-3 max-sm:gap-x-5 max-sm:rounded-lg max-sm:px-4 max-sm:py-1.5"
                                        />
                                    @endif

                                    {!! view_render_event('bagisto.shop.products.view.quantity.after', ['product' => $product]) !!}

                                    @if (core()->getConfigData('sales.checkout.shopping_cart.cart_page'))
                                        <!-- Add To Cart Button -->
                                        {!! view_render_event('bagisto.shop.products.view.add_to_cart.before', ['product' => $product]) !!}

                                        <x-shop::button
                                            type="submit"
                                            class="secondary-button w-full max-w-full max-md:py-3 max-sm:rounded-lg max-sm:py-1.5"
                                            button-type="secondary-button"
                                            :loading="false"
                                            :title="trans('shop::app.products.view.add-to-cart')"
                                            :disabled="! $product->isSaleable(1)"
                                            ::loading="isStoring.addToCart"
                                            ::disabled="isStoring.addToCart"
                                            @click="is_buy_now=0;"
                                        />

                                        {!! view_render_event('bagisto.shop.products.view.add_to_cart.after', ['product' => $product]) !!}
                                    @else
                                        <button
                                            type="button"
                                            class="secondary-button w-full max-w-full max-md:py-3 max-sm:rounded-lg max-sm:py-1.5"
                                            @click="$refs.contactUsModal.open()"
                                        >
                                            @lang('shop::app.components.layouts.footer.contact-us')
                                        </button>
                                    @endif
                                </div>

                                <!-- Buy Now Button -->
                                @if (core()->getConfigData('sales.checkout.shopping_cart.cart_page'))
                                    {!! view_render_event('bagisto.shop.products.view.buy_now.before', ['product' => $product]) !!}

                                    @if (core()->getConfigData('catalog.products.storefront.buy_now_button_display') !== '0')
                                        <!-- Go To Checkout — shown when cart already has items -->
                                        <a
                                            v-if="cartItemsQty > 0"
                                            href="{{ route('shop.checkout.onepage.index') }}"
                                            class="primary-button mt-5 flex w-full max-w-[470px] items-center justify-center max-md:py-3 max-sm:mt-3 max-sm:rounded-lg max-sm:py-1.5"
                                        >
                                            Continue to Checkout
                                        </a>

                                        <!-- Buy Now — shown when cart is empty -->
                                        <x-shop::button
                                            v-else
                                            type="submit"
                                            class="primary-button mt-5 w-full max-w-[470px] max-md:py-3 max-sm:mt-3 max-sm:rounded-lg max-sm:py-1.5"
                                            button-type="primary-button"
                                            :title="trans('shop::app.products.view.buy-now')"
                                            :disabled="! $product->isSaleable(1)"
                                            ::loading="isStoring.buyNow"
                                            @click="is_buy_now=1;"
                                            ::disabled="isStoring.buyNow"
                                        />
                                    @endif

                                    {!! view_render_event('bagisto.shop.products.view.buy_now.after', ['product' => $product]) !!}
                                @endif

                                <!-- Shop The Look -->
                                <div class="mt-6 border-t border-gray-100 pt-6 dark:border-gray-700">
                                    @include('shop-the-look::shop.product-look-section')
                                </div>

                                @if ($product->totalQuantity() <= 0)
                                    <!-- Back in Stock Notification Form -->
                                    <div class="mt-5 w-full max-w-[470px] rounded-xl border border-orange-200 bg-orange-50 p-4">
                                        <p class="mb-3 text-sm font-semibold text-orange-700">
                                            📦 @lang('shop::app.products.view.out-of-stock-notify')
                                        </p>

                                        <form
                                            id="stock-notify-form"
                                            onsubmit="submitStockNotify(event, {{ $product->id }})"
                                            class="flex gap-2"
                                        >
                                            <input
                                                type="email"
                                                name="email"
                                                required
                                                placeholder="@lang('shop::app.products.view.notify-email-placeholder')"
                                                class="flex-1 rounded-lg border border-gray-200 px-3 py-2 text-sm outline-none focus:border-navyBlue focus:ring-1 focus:ring-navyBlue"
                                            />
                                            <button
                                                type="submit"
                                                class="secondary-button whitespace-nowrap"
                                            >@lang('shop::app.products.view.notify-me')</button>
                                        </form>

                                        <p id="stock-notify-msg" class="mt-2 hidden text-sm font-medium text-green-600"></p>
                                    </div>
                                @endif

                                {!! view_render_event('bagisto.shop.products.view.additional_actions.before', ['product' => $product]) !!}

                                <!-- Share Buttons -->
                                <div class="mt-10 flex gap-9 max-md:mt-4 max-md:flex-wrap max-sm:justify-center max-sm:gap-3">
                                    {!! view_render_event('bagisto.shop.products.view.compare.before', ['product' => $product]) !!}

                                    <div
                                        class="flex cursor-pointer items-center justify-center gap-2.5 max-sm:gap-1.5 max-sm:text-base"
                                        role="button"
                                        tabindex="0"
                                        @click="is_buy_now=0; addToCompare({{ $product->id }})"
                                    >
                                        @if (core()->getConfigData('catalog.products.settings.compare_option'))
                                            <span
                                                class="icon-compare text-2xl"
                                                role="presentation"
                                            ></span>

                                            @lang('shop::app.products.view.compare')
                                        @endif
                                    </div>

                                    {!! view_render_event('bagisto.shop.products.view.compare.after', ['product' => $product]) !!}
                                </div>

                                {!! view_render_event('bagisto.shop.products.view.additional_actions.after', ['product' => $product]) !!}
                            </div>
                        </div>
                    </div>
                </form>
            </x-shop::form>

            <!-- Sticky Add to Cart Bar -->
            <div
                class="fixed bottom-0 left-0 right-0 z-[100] bg-white shadow-[0_-2px_16px_rgba(0,0,0,0.12)] transition-transform duration-300"
                :class="showStickyBar ? 'translate-y-0' : 'translate-y-full'"
            >
                <div class="container flex items-center justify-between gap-4 px-[60px] py-3 max-sm:px-4 max-sm:py-2">
                    <div class="flex min-w-0 items-center gap-3">
                        @if ($product->base_image)
                            <img
                                src="{{ $product->base_image->small_image_url }}"
                                class="h-12 w-12 flex-shrink-0 rounded-lg object-cover"
                                alt="{{ $product->name }}"
                            >
                        @endif

                        <div class="min-w-0">
                            <p class="truncate font-medium text-gray-900 max-sm:text-sm">{{ $product->name }}</p>
                            <p class="text-sm text-gray-500 max-sm:text-xs">{!! $product->getTypeInstance()->getPriceHtml() !!}</p>
                        </div>
                    </div>

                    <button
                        type="button"
                        class="primary-button flex-shrink-0 whitespace-nowrap max-sm:px-4 max-sm:py-2 max-sm:text-sm"
                        :disabled="isStoring.addToCart"
                        @click="triggerAddToCart"
                    >
                        <span v-if="! isStoring.addToCart">@lang('shop::app.products.view.add-to-cart')</span>
                        <span v-else class="icon-spinner animate-spin text-xl"></span>
                    </button>
                </div>
            </div>

            <!-- Contact Us Modal -->
            <x-shop::modal ref="contactUsModal">
                <x-slot:header>
                <h2 class="text-lg font-semibold max-md:text-base">
                        @lang('shop::app.products.view.contact-us.title')
                    </h2>
                </x-slot>

                <x-slot:content>
                    <x-shop::form :action="route('shop.home.contact_us.send_mail')">
                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="required">
                                @lang('shop::app.products.view.contact-us.name')
                            </x-shop::form.control-group.label>

                            <x-shop::form.control-group.control
                                type="text"
                                name="name"
                                rules="required"
                                :value="old('name')"
                                :label="trans('shop::app.products.view.contact-us.name')"
                                :placeholder="trans('shop::app.products.view.contact-us.name')"
                                :aria-label="trans('shop::app.products.view.contact-us.name')"
                                aria-required="true"
                            />

                            <x-shop::form.control-group.error control-name="name" />
                        </x-shop::form.control-group>

                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="required">
                                @lang('shop::app.products.view.contact-us.email')
                            </x-shop::form.control-group.label>

                            <x-shop::form.control-group.control
                                type="email"
                                name="email"
                                rules="required|email"
                                :value="old('email')"
                                :label="trans('shop::app.products.view.contact-us.email')"
                                :placeholder="trans('shop::app.products.view.contact-us.email')"
                                :aria-label="trans('shop::app.products.view.contact-us.email')"
                                aria-required="true"
                            />

                            <x-shop::form.control-group.error control-name="email" />
                        </x-shop::form.control-group>

                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label>
                                @lang('shop::app.products.view.contact-us.phone-number')
                            </x-shop::form.control-group.label>

                            <x-shop::form.control-group.control
                                type="text"
                                name="contact"
                                rules="phone"
                                :value="old('contact')"
                                :label="trans('shop::app.products.view.contact-us.phone-number')"
                                :placeholder="trans('shop::app.products.view.contact-us.phone-number')"
                                :aria-label="trans('shop::app.products.view.contact-us.phone-number')"
                            />

                            <x-shop::form.control-group.error control-name="contact" />
                        </x-shop::form.control-group>

                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="required">
                                @lang('shop::app.products.view.contact-us.desc')
                            </x-shop::form.control-group.label>

                            <x-shop::form.control-group.control
                                type="textarea"
                                name="message"
                                rules="required"
                                :label="trans('shop::app.products.view.contact-us.message')"
                                :placeholder="trans('shop::app.products.view.contact-us.describe-here')"
                                :aria-label="trans('shop::app.products.view.contact-us.message')"
                                aria-required="true"
                                rows="6"
                            />

                            <x-shop::form.control-group.error control-name="message" />
                        </x-shop::form.control-group>

                        @if (core()->getConfigData('customer.captcha.credentials.status'))
                            <x-shop::form.control-group class="mt-5">
                                {!! \Webkul\Customer\Facades\Captcha::render() !!}

                                <x-shop::form.control-group.error control-name="recaptcha_token" />
                            </x-shop::form.control-group>
                        @endif

                        <div class="mt-6 flex justify-end">
                            <button
                                type="submit"
                                class="primary-button rounded-2xl px-8 py-3 max-sm:rounded-lg max-sm:px-6 max-sm:py-2"
                            >
                                @lang('shop::app.products.view.contact-us.submit')
                            </button>
                        </div>
                    </x-shop::form>
                </x-slot>
            </x-shop::modal>
        </script>

        <script type="module">
            app.component('v-product', {
                template: '#v-product-template',

                data() {
                    return {
                        isWishlist: false,

                        isCustomer: '{{ auth()->guard('customer')->check() }}',

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

                    this.$axios.get('{{ route('shop.api.checkout.cart.index') }}')
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

                        this.$axios.post('{{ route("shop.api.checkout.cart.store") }}', formData, {
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
                            this.$axios.get('{{ route('shop.api.customers.account.wishlist.index') }}')
                                .then(response => {
                                    const wishlistItems = response.data.data || [];

                                    this.isWishlist = Boolean(wishlistItems.find(item => item.product.id == "{{ $product->id }}")?.product?.is_wishlist);
                                })
                                .catch(error => {});
                        }
                    },

                    addToWishlist() {
                        if (this.isCustomer) {
                            this.$axios.post('{{ route('shop.api.customers.account.wishlist.store') }}', {
                                    product_id: "{{ $product->id }}"
                                })
                                .then(response => {
                                    this.isWishlist = ! this.isWishlist;

                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });
                                })
                                .catch(error => {});
                        } else {
                            window.location.href = "{{ route('shop.customer.session.index')}}";
                        }
                    },

                    addToCompare(productId) {
                        /**
                         * This will handle for customers.
                         */
                        if (this.isCustomer) {
                            this.$axios.post('{{ route("shop.api.compare.store") }}', {
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

                                this.$emitter.emit('add-flash', { type: 'success', message: "@lang('shop::app.products.view.add-to-compare')" });
                            } else {
                                this.$emitter.emit('add-flash', { type: 'warning', message: "@lang('shop::app.products.view.already-in-compare')" });
                            }
                        } else {
                            this.setStorageValue(this.getCompareItemsStorageKey(), [productId]);

                            this.$emitter.emit('add-flash', { type: 'success', message: "@lang('shop::app.products.view.add-to-compare')" });
                        }
                    },

                    updateQty(quantity, id) {
                        this.isLoading = true;

                        let qty = {};

                        qty[id] = quantity;

                        this.$axios.put('{{ route('shop.api.checkout.cart.update') }}', { qty })
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
                    <x-shop::products.carousel
                        :title="trans('shop::app.products.view.related-product-title')"
                        :src="route('shop.api.products.related.index', ['id' => $product->id])"
                    />

                    <!-- Up-sell Products -->
                    <x-shop::products.carousel
                        :title="trans('shop::app.products.view.up-sell-title')"
                        :src="route('shop.api.products.up-sell.index', ['id' => $product->id])"
                    />
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

        @if (core()->getConfigData('customer.captcha.credentials.status'))
            {!! \Webkul\Customer\Facades\Captcha::renderJS() !!}
        @endif
    @endPushOnce

</x-shop::layouts>
