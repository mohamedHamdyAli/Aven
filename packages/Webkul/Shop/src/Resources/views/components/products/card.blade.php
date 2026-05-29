<v-product-card
    {{ $attributes }}
    :product="product"
>
</v-product-card>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-product-card-template"
    >
        <!-- Grid Card -->
        <div
            class="1180:transtion-all group relative w-full rounded-md 1180:grid 1180:content-start 1180:overflow-hidden 1180:duration-300 1180:hover:shadow-[0_5px_10px_rgba(0,0,0,0.1)]"
            v-if="mode != 'list'"
        >
            <div class="shop-card-img-wrap relative max-h-[300px] max-w-[291px] overflow-hidden max-md:max-h-60 max-md:max-w-full max-md:rounded-lg max-sm:max-h-[200px] max-sm:max-w-full">
                {!! view_render_event('bagisto.shop.components.products.card.image.before') !!}

                <!-- Product Image -->
                <a
                    :href="'{{ route('shop.product_or_category.index', ':slug') }}'.replace(':slug', product.url_key)"
                    :aria-label="product.name + ' '"
                >
                    <x-shop::media.images.lazy
                        class="after:content-[' '] relative bg-zinc-100 transition-all duration-300 after:block after:pb-[calc(100%+9px)] group-hover:scale-105"
                        ::src="product.base_image?.medium_image_url"
                        ::srcset="`
                            ${product.base_image?.small_image_url} 150w,
                            ${product.base_image?.medium_image_url} 300w,
                        `"
                        sizes="(max-width: 768px) 150px, (max-width: 1200px) 300px, 600px"
                        ::key="product.id"
                        ::index="product.id"
                        width="291"
                        height="300"
                        ::alt="product.name"
                    />
                </a>

                {!! view_render_event('bagisto.shop.components.products.card.image.after') !!}

                <!-- Product Ratings -->
                {!! view_render_event('bagisto.shop.components.products.card.average_ratings.before') !!}

                @if (core()->getConfigData('catalog.products.review.summary') == 'star_counts')
                    <x-shop::products.ratings
                        class="absolute bottom-1.5 items-center !border-white bg-white/80 !px-2 !py-1 text-xs max-sm:!px-1.5 max-sm:!py-0.5 ltr:left-1.5 rtl:right-1.5"
                        ::average="product.ratings.average"
                        ::total="product.ratings.total"
                        ::rating="false"
                        v-if="product.ratings.total"
                    />
                @else
                    <x-shop::products.ratings
                        class="absolute bottom-1.5 items-center !border-white bg-white/80 !px-2 !py-1 text-xs max-sm:!px-1.5 max-sm:!py-0.5 ltr:left-1.5 rtl:right-1.5"
                        ::average="product.ratings.average"
                        ::total="product.reviews.total"
                        ::rating="false"
                        v-if="product.reviews.total"
                    />
                @endif

                {!! view_render_event('bagisto.shop.components.products.card.average_ratings.after') !!}

                <div class="action-items bg-black">
                    <!-- Product Badges Stack -->
                    <div class="absolute top-1.5 flex flex-col gap-1 ltr:left-1.5 rtl:right-1.5">
                        <!-- Sale Badge -->
                        <p
                            class="inline-block self-start rounded-[44px] bg-red-600 px-2.5 text-sm text-white max-sm:rounded-r-xl max-sm:px-2 max-sm:py-0.5 max-sm:text-xs"
                            v-if="product.on_sale"
                        >
                            @lang('shop::app.components.products.card.sale')
                        </p>

                        <!-- New Badge -->
                        <p
                            class="inline-block self-start rounded-[44px] bg-navyBlue px-2.5 text-sm text-white max-sm:rounded-r-xl max-sm:px-2 max-sm:py-0.5 max-sm:text-xs"
                            v-if="product.is_new && ! product.on_sale"
                        >
                            @lang('shop::app.components.products.card.new')
                        </p>

                        <!-- Low Stock Badge -->
                        <p
                            class="inline-block self-start rounded-[44px] bg-orange-500 px-2.5 text-sm text-white max-sm:rounded-r-xl max-sm:px-2 max-sm:py-0.5 max-sm:text-xs"
                            v-if="product.stock_qty > 0 && product.stock_qty <= 5"
                        >
                            @lang('shop::app.components.products.card.low-stock')
                        </p>

                        <!-- Popular Badge -->
                        <p
                            class="inline-block self-start rounded-[44px] bg-emerald-600 px-2.5 text-sm text-white max-sm:rounded-r-xl max-sm:px-2 max-sm:py-0.5 max-sm:text-xs"
                            v-if="product.ratings.total >= 10"
                        >
                            @lang('shop::app.components.products.card.popular')
                        </p>

                        <!-- Flash Sale Badge -->
                        <p
                            class="inline-block self-start rounded-[44px] bg-red-600 px-2.5 text-xs text-white max-sm:rounded-r-xl max-sm:px-2 max-sm:py-0.5"
                            v-if="product.flash_sale_ends_at && !product.on_sale"
                        >
                            ⚡ Flash Sale
                        </p>
                    </div>

                    <div class="opacity-0 transition-all duration-300 group-hover:bottom-0 group-hover:opacity-100 max-lg:opacity-100 max-sm:opacity-100">

                        {!! view_render_event('bagisto.shop.components.products.card.wishlist_option.before') !!}

                        @if (core()->getConfigData('customer.settings.wishlist.wishlist_option'))
                            <span
                                class="absolute top-2.5 flex h-6 w-6 items-center justify-center rounded-full border border-zinc-200 bg-white text-lg md:hidden ltr:right-1.5 rtl:left-1.5"
                                role="button"
                                aria-label="@lang('shop::app.components.products.card.add-to-wishlist')"
                                tabindex="0"
                                :class="product.is_wishlist ? 'icon-heart-fill text-red-500' : 'icon-heart'"
                                @click="addToWishlist()"
                            >
                            </span>
                        @endif

                        {!! view_render_event('bagisto.shop.components.products.card.wishlist_option.after') !!}

                        {!! view_render_event('bagisto.shop.components.products.card.compare_option.before') !!}

                        @if (core()->getConfigData('catalog.products.settings.compare_option'))
                            <span
                                class="icon-compare absolute top-10 flex h-6 w-6 items-center justify-center rounded-full border border-zinc-200 bg-white text-lg sm:hidden ltr:right-1.5 rtl:left-1.5"
                                role="button"
                                aria-label="@lang('shop::app.components.products.card.add-to-compare')"
                                tabindex="0"
                                @click="addToCompare(product.id)"
                            >
                            </span>
                        @endif

                        {!! view_render_event('bagisto.shop.components.products.card.compare_option.after') !!}

                    </div>

                    <!-- Quick View Button -->
                    <button
                        class="absolute bottom-0 left-0 right-0 bg-black/70 py-2 text-sm font-medium text-white opacity-0 transition-all duration-300 group-hover:opacity-100 max-md:hidden"
                        @click.prevent="showQuickView = true"
                    >
                        @lang('shop::app.components.products.card.quick-view')
                    </button>
                </div>

                <!-- Quick Add overlay — gradient, anchored to bottom of image -->
                <div
                    v-if="showQuickAdd"
                    class="absolute inset-x-0 top-0 z-20 flex flex-col justify-end"
                    :style="{ height: quickAddHeight, background: 'linear-gradient(to top, rgba(0,0,0,0.93) 0%, rgba(0,0,0,0.6) 55%, rgba(0,0,0,0) 100%)' }"
                >
                    <!-- Close top-right -->
                    <button
                        style="position:absolute;top:8px;right:8px;width:26px;height:26px;border-radius:50%;background:rgba(0,0,0,0.35);border:none;cursor:pointer;color:white;font-size:15px;display:flex;align-items:center;justify-content:center;line-height:1"
                        @click.stop="showQuickAdd = false"
                    >&times;</button>

                    <!-- Loading -->
                    <div v-if="quickAddLoading" style="display:flex;justify-content:center;padding:24px 0">
                        <svg style="width:20px;height:20px;animation:spin 1s linear infinite;color:rgba(255,255,255,0.5)" fill="none" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" style="opacity:.25"></circle>
                            <path fill="currentColor" d="M4 12a8 8 0 018-8v8z" style="opacity:.75"></path>
                        </svg>
                    </div>

                    <!-- Attributes + button -->
                    <template v-else>
                        <div style="padding:0 12px 8px">
                            <!-- Each attribute -->
                            <div v-for="attribute in quickAddAttributes" :key="attribute.id" style="margin-bottom:10px">
                                <div style="display:flex;align-items:center;gap:6px;margin-bottom:7px">
                                    <span style="font-size:10px;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.55)">@{{ attribute.label }}</span>
                                    <span v-if="quickAddSelected[attribute.id]" style="font-size:12px;font-weight:600;color:#fff">
                                        @{{ attribute.options.find(o => o.id == quickAddSelected[attribute.id])?.label }}
                                    </span>
                                    <span v-else style="font-size:11px;color:rgba(255,255,255,.35);font-style:italic">اختر</span>
                                </div>
                                <div style="display:flex;flex-wrap:wrap;gap:5px">
                                    <button
                                        v-for="option in attribute.options"
                                        :key="option.id"
                                        type="button"
                                        style="border-radius:7px;padding:5px 11px;font-size:12px;font-weight:700;border:1.5px solid transparent;cursor:pointer;transition:all .1s"
                                        :style="quickAddSelected[attribute.id] == option.id
                                            ? { background:'#fff', color:'#111', borderColor:'#fff' }
                                            : { background:'rgba(255,255,255,.12)', color:'rgba(255,255,255,.85)', borderColor:'rgba(255,255,255,.2)' }"
                                        @click.stop="quickAddSelected = { ...quickAddSelected, [attribute.id]: option.id }"
                                    >@{{ option.label }}</button>
                                </div>
                            </div>

                            <!-- Add To Cart button -->
                            <button
                                style="width:100%;border-radius:10px;padding:11px;font-size:13px;font-weight:700;border:none;margin-top:4px;transition:all .15s"
                                :style="quickAddAllSelected && !isAddingToCart
                                    ? { background:'#fff', color:'#111', cursor:'pointer' }
                                    : { background:'rgba(255,255,255,.18)', color:'rgba(255,255,255,.35)', cursor:'not-allowed' }"
                                :disabled="!quickAddAllSelected || isAddingToCart"
                                @click.stop="addToCartWithVariant"
                            >
                                <span v-if="isAddingToCart" style="display:flex;align-items:center;justify-content:center;gap:5px">
                                    <svg style="width:13px;height:13px;animation:spin 1s linear infinite" fill="none" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" style="opacity:.25"></circle>
                                        <path fill="currentColor" d="M4 12a8 8 0 018-8v8z" style="opacity:.75"></path>
                                    </svg>
                                </span>
                                <span v-else>@lang('shop::app.components.products.card.add-to-cart')</span>
                            </button>
                        </div>
                    </template>
                </div>

            </div>


            <!-- Product Information Section -->
            <div class="-mt-9 grid max-w-[291px] translate-y-9 content-start gap-2.5 bg-white p-2.5 transition-transform duration-300 ease-out group-hover:-translate-y-0 group-hover:rounded-t-lg max-md:relative max-md:mt-0 max-md:translate-y-0 max-md:gap-0 max-md:px-0 max-md:py-1.5 max-sm:min-w-[170px] max-sm:max-w-[192px]">

                {!! view_render_event('bagisto.shop.components.products.card.name.before') !!}

                <p class="break-words text-base font-medium max-md:mb-1.5 max-md:max-w-56 max-md:whitespace-break-spaces max-md:leading-6 max-sm:max-w-[192px] max-sm:text-sm max-sm:leading-4">
                    @{{ product.name }}
                </p>

                {!! view_render_event('bagisto.shop.components.products.card.name.after') !!}

                <!-- Pricing -->
                {!! view_render_event('bagisto.shop.components.products.card.price.before') !!}

                <div
                    class="flex flex-wrap items-center gap-x-2.5 gap-y-0.5 text-lg font-semibold max-sm:text-sm max-sm:leading-6"
                    v-html="product.price_html"
                >
                </div>

                {!! view_render_event('bagisto.shop.components.products.card.price.after') !!}

                <!-- Product Actions Section -->
                <div class="action-items flex items-center justify-between opacity-0 transition-all duration-300 ease-in-out group-hover:opacity-100 max-md:hidden">
                    @if (core()->getConfigData('sales.checkout.shopping_cart.cart_page'))
                        {!! view_render_event('bagisto.shop.components.products.card.add_to_cart.before') !!}

                        <button
                            class="secondary-button w-full max-w-full p-2.5 text-sm font-medium max-sm:rounded-xl max-sm:p-2"
                            :disabled="! product.is_saleable || isAddingToCart"
                            @click="addToCart()"
                        >
                            @lang('shop::app.components.products.card.add-to-cart')
                        </button>

                        {!! view_render_event('bagisto.shop.components.products.card.add_to_cart.after') !!}
                    @endif

                    {!! view_render_event('bagisto.shop.components.products.card.wishlist_option.before') !!}

                    @if (core()->getConfigData('customer.settings.wishlist.wishlist_option'))
                        <span
                            class="cursor-pointer p-2.5 text-2xl max-sm:hidden"
                            role="button"
                            aria-label="@lang('shop::app.components.products.card.add-to-wishlist')"
                            tabindex="0"
                            :class="product.is_wishlist ? 'icon-heart-fill text-red-600' : 'icon-heart'"
                            @click="addToWishlist()"
                        >
                        </span>
                    @endif

                    {!! view_render_event('bagisto.shop.components.products.card.wishlist_option.after') !!}

                    {!! view_render_event('bagisto.shop.components.products.card.compare_option.before') !!}

                    @if (core()->getConfigData('catalog.products.settings.compare_option'))
                        <span
                            class="icon-compare cursor-pointer p-2.5 text-2xl max-sm:hidden"
                            role="button"
                            aria-label="@lang('shop::app.components.products.card.add-to-compare')"
                            tabindex="0"
                            @click="addToCompare(product.id)"
                        >
                        </span>
                    @endif

                    {!! view_render_event('bagisto.shop.components.products.card.compare_option.after') !!}
                </div>
            </div>
        </div>

        <!-- List Card -->
        <div
            class="relative flex max-w-max grid-cols-2 gap-4 overflow-hidden rounded max-sm:flex-wrap"
            v-else
        >
            <div class="group relative max-h-[258px] max-w-[250px] overflow-hidden">

                {!! view_render_event('bagisto.shop.components.products.card.image.before') !!}

                <a :href="'{{ route('shop.product_or_category.index', ':slug') }}'.replace(':slug', product.url_key)">
                    <x-shop::media.images.lazy
                        class="after:content-[' '] relative min-w-[250px] bg-zinc-100 transition-all duration-300 after:block after:pb-[calc(100%+9px)] group-hover:scale-105"
                        ::src="product.base_image?.medium_image_url"
                        ::key="product.id"
                        ::index="product.id"
                        width="291"
                        height="300"
                        ::alt="product.name"
                    />
                </a>

                {!! view_render_event('bagisto.shop.components.products.card.image.after') !!}

                <div class="action-items bg-black">
                    <p
                        class="absolute top-5 inline-block rounded-[44px] bg-red-500 px-2.5 text-sm text-white ltr:left-5 max-sm:ltr:left-2 rtl:right-5"
                        v-if="product.on_sale"
                    >
                        @lang('shop::app.components.products.card.sale')
                    </p>

                    <p
                        class="absolute top-5 inline-block rounded-[44px] bg-navyBlue px-2.5 text-sm text-white ltr:left-5 max-sm:ltr:left-2 rtl:right-5"
                        v-else-if="product.is_new"
                    >
                        @lang('shop::app.components.products.card.new')
                    </p>

                    <div class="opacity-0 transition-all duration-300 group-hover:bottom-0 group-hover:opacity-100 max-sm:opacity-100">

                        {!! view_render_event('bagisto.shop.components.products.card.wishlist_option.before') !!}

                        @if (core()->getConfigData('customer.settings.wishlist.wishlist_option'))
                            <span
                                class="absolute top-5 flex h-[30px] w-[30px] cursor-pointer items-center justify-center rounded-md bg-white text-2xl ltr:right-5 rtl:left-5"
                                role="button"
                                aria-label="@lang('shop::app.components.products.card.add-to-wishlist')"
                                tabindex="0"
                                :class="product.is_wishlist ? 'icon-heart-fill text-red-600' : 'icon-heart'"
                                @click="addToWishlist()"
                            >
                            </span>
                        @endif

                        {!! view_render_event('bagisto.shop.components.products.card.wishlist_option.after') !!}

                        {!! view_render_event('bagisto.shop.components.products.card.compare_option.before') !!}

                        @if (core()->getConfigData('catalog.products.settings.compare_option'))
                            <span
                                class="icon-compare absolute top-16 flex h-[30px] w-[30px] cursor-pointer items-center justify-center rounded-md bg-white text-2xl ltr:right-5 rtl:left-5"
                                role="button"
                                aria-label="@lang('shop::app.components.products.card.add-to-compare')"
                                tabindex="0"
                                @click="addToCompare(product.id)"
                            >
                            </span>
                        @endif

                        {!! view_render_event('bagisto.shop.components.products.card.compare_option.after') !!}
                    </div>
                </div>
            </div>

            <div class="grid content-start gap-4">

                {!! view_render_event('bagisto.shop.components.products.card.name.before') !!}

                <p class="text-base">
                    @{{ product.name }}
                </p>

                {!! view_render_event('bagisto.shop.components.products.card.name.after') !!}

                {!! view_render_event('bagisto.shop.components.products.card.price.before') !!}

                <div
                    class="flex flex-wrap items-center gap-x-2.5 gap-y-0.5 text-lg font-semibold"
                    v-html="product.price_html"
                >
                </div>

                {!! view_render_event('bagisto.shop.components.products.card.price.after') !!}

                <!-- Needs to implement that in future -->
                <div class="flex hidden gap-4">
                    <span class="block h-[30px] w-[30px] rounded-full bg-[#B5DCB4]">
                    </span>

                    <span class="block h-[30px] w-[30px] rounded-full bg-zinc-500">
                    </span>
                </div>

                {!! view_render_event('bagisto.shop.components.products.card.average_ratings.before') !!}

                <p class="text-sm text-zinc-500">
                    <template  v-if="! product.ratings.total">
                        <p class="text-sm text-zinc-500">
                            @lang('shop::app.components.products.card.review-description')
                        </p>
                    </template>

                    <template v-else>
                        @if (core()->getConfigData('catalog.products.review.summary') == 'star_counts')
                            <x-shop::products.ratings
                                ::average="product.ratings.average"
                                ::total="product.ratings.total"
                                ::rating="false"
                            />
                        @else
                            <x-shop::products.ratings
                                ::average="product.ratings.average"
                                ::total="product.reviews.total"
                                ::rating="false"
                            />
                        @endif
                    </template>
                </p>

                {!! view_render_event('bagisto.shop.components.products.card.average_ratings.after') !!}

                @if (core()->getConfigData('sales.checkout.shopping_cart.cart_page'))

                    {!! view_render_event('bagisto.shop.components.products.card.add_to_cart.before') !!}

                    <x-shop::button
                        class="primary-button whitespace-nowrap px-8 py-2.5"
                        :title="trans('shop::app.components.products.card.add-to-cart')"
                        ::loading="isAddingToCart"
                        ::disabled="! product.is_saleable || isAddingToCart"
                        @click="addToCart()"
                    />

                    {!! view_render_event('bagisto.shop.components.products.card.add_to_cart.after') !!}

                @endif
            </div>
        </div>

        <!-- Quick View Modal (Vue 3 Teleport) -->
        <teleport to="body">
            <div
                v-if="showQuickView"
                class="fixed inset-0 z-[180] flex items-center justify-center bg-black/60 p-4"
                @click.self="showQuickView = false"
            >
                <div class="relative w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl">
                    <button
                        class="absolute right-4 top-4 z-10 flex h-8 w-8 items-center justify-center rounded-full bg-white/80 text-xl text-gray-500 hover:text-gray-800"
                        @click="showQuickView = false"
                    >&times;</button>

                    <div class="flex flex-col sm:flex-row">
                        <!-- Image -->
                        <div class="flex items-center justify-center bg-zinc-100 sm:w-1/2">
                            <img
                                :src="product.base_image?.large_image_url || product.base_image?.medium_image_url"
                                :alt="product.name"
                                class="h-full max-h-[350px] w-full object-contain"
                            />
                        </div>

                        <!-- Info -->
                        <div class="flex flex-col justify-center gap-4 p-6 sm:w-1/2">
                            <h2
                                class="text-xl font-semibold text-gray-900"
                                v-text="product.name"
                            ></h2>

                            <div
                                class="text-lg font-bold"
                                v-html="product.price_html"
                            ></div>

                            <div class="flex flex-wrap gap-3">
                                @if (core()->getConfigData('sales.checkout.shopping_cart.cart_page'))
                                    <button
                                        class="primary-button flex-1 justify-center"
                                        :disabled="! product.is_saleable || isAddingToCart"
                                        @click="addToCart(); showQuickView = false"
                                    >
                                        @lang('shop::app.components.products.card.add-to-cart')
                                    </button>
                                @endif

                                <a
                                    :href="'{{ route('shop.product_or_category.index', ':slug') }}'.replace(':slug', product.url_key)"
                                    class="secondary-button whitespace-nowrap"
                                >
                                    @lang('shop::app.components.products.card.view-full-details')
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </teleport>
    </script>

    <script type="module">
        app.component('v-product-card', {
            template: '#v-product-card-template',

            props: ['mode', 'product'],

            data() {
                return {
                    isCustomer: '{{ auth()->guard('customer')->check() }}',

                    isAddingToCart: false,

                    showQuickView: false,

                    showQuickAdd: false,

                    quickAddLoading: false,

                    quickAddAttributes: [],

                    quickAddSelected: {},
                }
            },

            computed: {
                quickAddAllSelected() {
                    if (! this.quickAddAttributes.length) {
                        return false;
                    }

                    return this.quickAddAttributes.every(attr => !! this.quickAddSelected[attr.id]);
                },

                quickAddHeight() {
                    const w = typeof window !== 'undefined' ? window.innerWidth : 1200;

                    if (w < 640) return '200px';
                    if (w < 768) return '240px';

                    return '300px';
                },
            },

            methods: {
                addToWishlist() {
                    if (this.isCustomer) {
                        this.$axios.post(`{{ route('shop.api.customers.account.wishlist.store') }}`, {
                                product_id: this.product.id
                            })
                            .then(response => {
                                this.product.is_wishlist = ! this.product.is_wishlist;

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
                    let items = this.getStorageValue() ?? [];

                    if (items.length) {
                        if (! items.includes(productId)) {
                            items.push(productId);

                            localStorage.setItem('compare_items', JSON.stringify(items));

                            this.$emitter.emit('add-flash', { type: 'success', message: "@lang('shop::app.components.products.card.add-to-compare-success')" });
                        } else {
                            this.$emitter.emit('add-flash', { type: 'warning', message: "@lang('shop::app.components.products.card.already-in-compare')" });
                        }
                    } else {
                        localStorage.setItem('compare_items', JSON.stringify([productId]));

                        this.$emitter.emit('add-flash', { type: 'success', message: "@lang('shop::app.components.products.card.add-to-compare-success')" });

                    }
                },

                getStorageValue(key) {
                    let value = localStorage.getItem('compare_items');

                    if (! value) {
                        return [];
                    }

                    return JSON.parse(value);
                },

                addToCart() {
                    if (this.product.type === 'configurable') {
                        this.openQuickAdd();

                        return;
                    }

                    if (! this.product.type || this.product.type !== 'simple') {
                        window.location.href = '{{ route('shop.product_or_category.index', ':slug') }}'.replace(':slug', this.product.url_key);

                        return;
                    }

                    this.isAddingToCart = true;

                    this.$axios.post('{{ route("shop.api.checkout.cart.store") }}', {
                            'quantity': 1,
                            'product_id': this.product.id,
                        })
                        .then(response => {
                            if (response.data.message) {
                                this.$emitter.emit('update-mini-cart', response.data.data );

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                            } else {
                                this.$emitter.emit('add-flash', { type: 'warning', message: response.data.data.message });
                            }

                            this.isAddingToCart = false;
                        })
                        .catch(error => {
                            this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });

                            if (error.response.data.redirect_uri) {
                                window.location.href = error.response.data.redirect_uri;
                            }

                            this.isAddingToCart = false;
                        });
                },

                openQuickAdd() {
                    this.showQuickAdd = true;
                    this.quickAddLoading = true;
                    this.quickAddAttributes = [];
                    this.quickAddSelected = {};

                    this.$axios.get(`{{ route('shop.api.products.configurable.options', ':id') }}`.replace(':id', this.product.id))
                        .then(response => {
                            const data = response.data.data;

                            if (data && data.attributes) {
                                this.quickAddAttributes = data.attributes;
                            }

                            this.quickAddLoading = false;
                        })
                        .catch(() => {
                            this.quickAddLoading = false;
                            this.showQuickAdd = false;
                        });
                },

                addToCartWithVariant() {
                    if (! this.quickAddAllSelected || this.isAddingToCart) {
                        return;
                    }

                    this.isAddingToCart = true;

                    const superAttribute = {};

                    this.quickAddAttributes.forEach(attr => {
                        superAttribute[attr.id] = this.quickAddSelected[attr.id];
                    });

                    this.$axios.post('{{ route("shop.api.checkout.cart.store") }}', {
                            product_id: this.product.id,
                            quantity: 1,
                            super_attribute: superAttribute,
                        })
                        .then(response => {
                            this.isAddingToCart = false;
                            this.showQuickAdd = false;

                            if (response.data.message) {
                                this.$emitter.emit('update-mini-cart', response.data.data);

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                            } else {
                                this.$emitter.emit('add-flash', { type: 'warning', message: response.data.data.message });
                            }
                        })
                        .catch(error => {
                            this.isAddingToCart = false;

                            this.$emitter.emit('add-flash', { type: 'error', message: error.response?.data?.message || "@lang('shop::app.components.products.card.error-adding-to-cart')" });
                        });
                },
            },
        });
    </script>
@endpushOnce
