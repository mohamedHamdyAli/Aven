<!-- Header -->
<h1 class="text-2xl font-medium max-md:py-4 max-md:text-base">
    @lang('shop::app.checkout.onepage.summary.cart-summary')
</h1>

<!-- Cart Items -->
<div class="mt-10 grid border-b border-zinc-200 max-md:mt-3 max-sm:mt-0">
    <div
        class="flex gap-x-4 pb-5 max-md:gap-x-3 max-md:pb-4"
        v-for="item in cart.items"
    >
        {!! view_render_event('bagisto.shop.checkout.onepage.summary.item_image.before') !!}

        <img
            class="h-[90px] max-h-[90px] w-[90px] max-w-[90px] rounded-xl max-md:h-20 max-md:max-h-20 max-md:max-w-20 max-md:rounded-lg"
            :src="item.base_image.small_image_url"
            :alt="item.name"
            width="110"
            height="110"
        />

        {!! view_render_event('bagisto.shop.checkout.onepage.summary.item_image.after') !!}

        <div class="flex flex-1 flex-col justify-between">
            {!! view_render_event('bagisto.shop.checkout.onepage.summary.item_name.before') !!}

            <div class="flex items-start justify-between gap-2">
                <p class="text-base text-navyBlue max-md:text-sm max-md:font-medium">
                    @{{ item.name }}
                </p>

                <!-- Remove button -->
                <button
                    type="button"
                    class="shrink-0 text-gray-400 transition hover:text-red-500"
                    title="Remove"
                    @click="removeCartItem(item.id)"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {!! view_render_event('bagisto.shop.checkout.onepage.summary.item_name.after') !!}

            <!-- Variant options (Color, Size, etc.) -->
            <div v-if="item.options && item.options.length" class="mt-1.5 flex flex-wrap gap-1.5">
                <span
                    v-for="opt in item.options"
                    :key="opt.attribute_name"
                    class="inline-flex items-center gap-1 rounded-full border border-gray-200 bg-gray-50 px-2 py-0.5 text-xs text-gray-600"
                >
                    <span class="text-gray-400">@{{ opt.attribute_name }}</span>
                    <span class="font-semibold text-gray-800">@{{ opt.option_label }}</span>
                </span>
            </div>

            <div class="mt-2 flex items-center justify-between gap-3">
                <!-- Price -->
                <p class="text-base font-medium max-sm:text-sm">
                    <template v-if="displayTax.prices == 'including_tax'">@{{ item.formatted_price_incl_tax }}</template>
                    <template v-else>@{{ item.formatted_price }}</template>
                </p>

                <!-- Qty stepper -->
                <div class="flex items-center gap-1 rounded-lg border border-gray-200 px-1 py-0.5">
                    <button
                        type="button"
                        class="flex h-6 w-6 items-center justify-center rounded text-gray-500 transition hover:bg-gray-100 disabled:opacity-40"
                        :disabled="item.quantity <= 1"
                        @click="updateCartItemQty(item.id, item.quantity - 1)"
                    >−</button>

                    <span class="min-w-[20px] text-center text-sm font-medium">@{{ item.quantity }}</span>

                    <button
                        type="button"
                        class="flex h-6 w-6 items-center justify-center rounded text-gray-500 transition hover:bg-gray-100"
                        @click="updateCartItemQty(item.id, item.quantity + 1)"
                    >+</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cart Totals -->
<div class="mb-8 mt-6 grid gap-4 max-md:mb-0 max-sm:mt-4 max-sm:gap-2.5">
    <!-- Sub Total -->
    {!! view_render_event('bagisto.shop.checkout.onepage.summary.sub_total.before') !!}

    <template v-if="displayTax.subtotal == 'including_tax'">
        <div class="flex justify-between text-right">
            <p class="text-base max-sm:text-sm">
                @lang('shop::app.checkout.onepage.summary.sub-total')
            </p>

            <p class="text-base font-medium max-sm:text-sm">
                @{{ cart.formatted_sub_total_incl_tax }}
            </p>
        </div>
    </template>

    <template v-else-if="displayTax.subtotal == 'both'">
        <div class="flex justify-between text-right">
            <p class="text-base max-sm:text-sm">
                @lang('shop::app.checkout.onepage.summary.sub-total')
            </p>

            <div>
                <p class="text-base font-medium max-sm:text-sm">
                    @{{ cart.formatted_sub_total }}
                </p>

                <p class="text-xs italic text-gray-500 dark:text-gray-400">
                    @lang('shop::app.checkout.onepage.summary.incl-tax') @{{ cart.formatted_sub_total_incl_tax }}
                </p>
            </div>
        </div>
    </template>

    <template v-else>
        <div class="flex justify-between text-right">
            <p class="text-base max-sm:text-sm">
                @lang('shop::app.checkout.onepage.summary.sub-total')
            </p>

            <p class="text-base font-medium max-sm:text-sm">
                @{{ cart.formatted_sub_total }}
            </p>
        </div>
    </template>

    {!! view_render_event('bagisto.shop.checkout.onepage.summary.sub_total.after') !!}

    <!-- Discount -->
    {!! view_render_event('bagisto.shop.checkout.onepage.summary.discount_amount.before') !!}

    <template v-if="cart.discount_amount && parseFloat(cart.discount_amount) > 0">
        <!-- Single Source: Simple line. -->
        <div
            class="flex justify-between text-right"
            v-if="parseFloat(cart.items_discount_amount || 0) <= 0 || parseFloat(cart.shipping_discount_amount || 0) <= 0"
        >
            <p class="text-base text-red-600 max-sm:text-sm">
                @lang('shop::app.checkout.onepage.summary.discount-amount')
            </p>

            <p class="text-base font-medium text-red-600 max-sm:text-sm">
                - @{{ cart.formatted_discount_amount }}
            </p>
        </div>

        <!-- Multi Source: Expandable breakdown. -->
        <div
            class="flex flex-col gap-2 border-y py-2"
            v-else
        >
            <div
                class="flex cursor-pointer justify-between text-right"
                @click="cart.show_discount_breakdown = ! cart.show_discount_breakdown"
            >
                <p class="text-base text-red-600 max-sm:text-sm">
                    @lang('shop::app.checkout.onepage.summary.discount-amount')
                </p>

                <p class="flex items-center gap-1 text-base font-medium text-red-600 max-sm:text-sm">
                    - @{{ cart.formatted_discount_amount }}

                    <span
                        class="text-xl"
                        :class="{'icon-arrow-up': cart.show_discount_breakdown, 'icon-arrow-down': ! cart.show_discount_breakdown}"
                    ></span>
                </p>
            </div>

            <div
                class="flex flex-col gap-1"
                v-show="cart.show_discount_breakdown"
            >
                <div class="flex justify-between gap-1 text-right">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        @lang('shop::app.checkout.onepage.summary.items-discount')
                    </p>

                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        - @{{ cart.formatted_items_discount_amount }}
                    </p>
                </div>

                <div class="flex justify-between gap-1 text-right">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        @lang('shop::app.checkout.onepage.summary.shipping-discount')
                    </p>

                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        - @{{ cart.formatted_shipping_discount_amount }}
                    </p>
                </div>
            </div>
        </div>
    </template>

    {!! view_render_event('bagisto.shop.checkout.onepage.summary.discount_amount.after') !!}

    <!-- Apply Coupon -->
    {!! view_render_event('bagisto.shop.checkout.onepage.summary.coupon.before') !!}

    @include('shop::checkout.coupon')

    {!! view_render_event('bagisto.shop.checkout.onepage.summary.coupon.after') !!}

    <!-- Shipping Rates -->
    {!! view_render_event('bagisto.shop.checkout.onepage.summary.delivery_charges.before') !!}

    <div class="flex justify-between text-right">
        <p class="text-base max-sm:text-sm">
            @lang('shop::app.checkout.onepage.summary.delivery-charges')
        </p>

        <p class="text-base font-medium max-sm:text-sm">
            <template v-if="egyptShippingPreview">
                <span class="text-navyBlue">+ @{{ egyptShippingPreview }}</span>
            </template>
            <template v-else-if="displayTax.shipping == 'including_tax'">
                + @{{ cart.formatted_shipping_amount_incl_tax }}
            </template>
            <template v-else>
                + @{{ cart.formatted_shipping_amount }}
            </template>
        </p>
    </div>

    {!! view_render_event('bagisto.shop.checkout.onepage.summary.delivery_charges.after') !!}


    <!-- Taxes -->
    {!! view_render_event('bagisto.shop.checkout.onepage.summary.tax.before') !!}

    <div
        class="flex justify-between text-right"
        v-if="! cart.tax_total"
    >
        <p class="text-base max-md:font-normal max-sm:text-sm">
            @lang('shop::app.checkout.onepage.summary.tax')
        </p>

        <p class="text-lg font-semibold max-sm:text-sm">
            + @{{ cart.formatted_tax_total }}
        </p>
    </div>

    <div
        class="flex flex-col gap-2 border-y py-2"
        v-else
    >
        <div
            class="flex cursor-pointer justify-between text-right"
            @click="cart.show_taxes = ! cart.show_taxes"
        >
            <p class="text-base max-md:font-normal max-sm:text-sm">
                @lang('shop::app.checkout.onepage.summary.tax')
            </p>

            <p class="flex items-center gap-1.5 text-base font-medium max-sm:text-sm">
                <template v-if="displayTax.subtotal === 'including_tax'">
                    @{{ cart.formatted_tax_total }}

                    <span class="text-xs italic font-normal text-gray-500 dark:text-gray-400">
                        (@lang('shop::app.checkout.onepage.summary.included'))
                    </span>
                </template>

                <template v-else>+ @{{ cart.formatted_tax_total }}</template>

                <span
                    class="text-xl"
                    :class="{'icon-arrow-up': cart.show_taxes, 'icon-arrow-down': ! cart.show_taxes}"
                ></span>
            </p>
        </div>

        <div
            class="flex flex-col gap-1"
            v-show="cart.show_taxes"
        >
            <div
                class="flex justify-between gap-1 text-right"
                v-for="(amount, index) in cart.applied_taxes"
            >
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    @{{ index }}
                </p>

                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    <template v-if="displayTax.subtotal === 'including_tax'">@{{ amount }}</template>
                    
                    <template v-else>+ @{{ amount }}</template>
                </p>
            </div>
        </div>
    </div>

    {!! view_render_event('bagisto.shop.checkout.onepage.summary.tax.after') !!}

    <!-- Cart Grand Total -->
    <!-- COD Handling Fee -->
    @php $codFee = (float) core()->getConfigData('sales.payment_methods.cashondelivery.extra_charge'); @endphp
    @if ($codFee > 0)
        <div class="flex justify-between text-right" v-if="cart.payment?.method === 'cashondelivery'">
            <p class="text-base max-sm:text-sm">Cash on Delivery Fee</p>
            <p class="text-base font-medium max-sm:text-sm">+ {{ core()->formatPrice($codFee) }}</p>
        </div>
    @endif

    @auth('customer')
    <div x-data="walletWidget()" class="mt-3 rounded-lg border border-indigo-100 bg-indigo-50 p-3">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-indigo-700">Store Credit</p>
                <p class="text-xs text-indigo-500">Available: <span x-text="formattedBalance"></span></p>
            </div>
            <template x-if="!applied">
                <button @click="apply()" class="rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-700">
                    Use Credit
                </button>
            </template>
            <template x-if="applied">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-semibold text-green-600">-<span x-text="formattedApplied"></span></span>
                    <button @click="remove()" class="text-xs text-red-500 hover:text-red-700">Remove</button>
                </div>
            </template>
        </div>
    </div>
    @endauth

    {{-- Gift Card --}}
    <div
        x-data="giftCardWidget()"
        x-init="init()"
        class="mt-3 rounded-lg border border-amber-100 bg-amber-50 p-3"
    >
        <p class="text-sm font-semibold text-amber-700 mb-2">🎁 Gift Card</p>
        <template x-if="!applied">
            <div class="flex gap-2">
                <input
                    type="text"
                    x-model="code"
                    placeholder="Enter gift card code"
                    class="flex-1 rounded-lg border border-amber-200 px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-amber-400"
                />
                <button
                    @click="apply()"
                    class="rounded-lg bg-amber-500 px-3 py-1.5 text-xs font-semibold text-white hover:bg-amber-600"
                >Apply</button>
            </div>
        </template>
        <template x-if="applied">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs text-amber-600 font-mono" x-text="appliedCode"></span>
                    <span class="ml-2 text-sm font-semibold text-green-600">-<span x-text="formattedDiscount"></span></span>
                </div>
                <button @click="remove()" class="text-xs text-red-500 hover:text-red-700">Remove</button>
            </div>
        </template>
        <p x-show="message" x-text="message" class="mt-1 text-xs text-red-600"></p>
    </div>

    {{-- Loyalty Points --}}
    @auth('customer')
        @php
            $loyaltyEnabled = (bool) core()->getConfigData('general.loyalty.settings.enabled');
            $loyaltyBalance = 0;
            if ($loyaltyEnabled) {
                try {
                    $loyaltyBalance = app(\Webkul\Loyalty\Services\LoyaltyService::class)
                        ->getBalance(auth()->guard('customer')->id());
                } catch (\Throwable $e) {}
            }
        @endphp
        @if ($loyaltyEnabled && $loyaltyBalance >= (float)(core()->getConfigData('general.loyalty.settings.min_redeem') ?? 100))
            <div
                x-data="loyaltyWidget()"
                class="mt-4 border-t border-zinc-200 pt-4"
            >
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-navyBlue">&#11088; Loyalty Points</span>
                    <span class="text-sm text-gray-500">{{ number_format($loyaltyBalance) }} pts available</span>
                </div>

                <template x-if="! applied">
                    <div class="mt-2 flex gap-2">
                        <input
                            x-model="points"
                            type="number"
                            min="{{ (int)(core()->getConfigData('general.loyalty.settings.min_redeem') ?? 100) }}"
                            max="{{ (int)$loyaltyBalance }}"
                            placeholder="Points to redeem"
                            class="flex-1 rounded border border-zinc-300 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-navyBlue"
                        />
                        <button
                            @click="applyPoints()"
                            class="rounded bg-navyBlue px-4 py-2 text-sm font-medium text-white hover:bg-blue-800"
                        >
                            Apply
                        </button>
                    </div>
                </template>

                <template x-if="applied">
                    <div class="mt-2 flex items-center justify-between rounded-md bg-green-50 px-3 py-2">
                        <span class="text-sm text-green-700">- <span x-text="formattedDiscount"></span></span>
                        <button
                            @click="removePoints()"
                            class="text-xs text-red-500 hover:underline"
                        >
                            Remove
                        </button>
                    </div>
                </template>
            </div>
        @endif
    @endauth

    {!! view_render_event('bagisto.shop.checkout.onepage.summary.grand_total.before') !!}

    <div class="flex justify-between text-right">
        <p class="text-lg font-semibold max-sm:text-sm">
            @lang('shop::app.checkout.onepage.summary.grand-total')
        </p>

        <p class="text-lg font-semibold max-sm:text-sm">
            @{{ cart.formatted_grand_total }}
        </p>
    </div>

    {!! view_render_event('bagisto.shop.checkout.onepage.summary.grand_total.after') !!}

    <!-- Checkout Trust Badges -->
    <div class="mt-5 grid grid-cols-2 gap-2.5 border-t border-gray-100 pt-5">
        <div class="flex items-center gap-2 text-xs text-gray-500">
            <svg class="h-5 w-5 shrink-0 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            <span>@lang('shop::app.checkout.cart.summary.trust.secure')</span>
        </div>
        <div class="flex items-center gap-2 text-xs text-gray-500">
            <svg class="h-5 w-5 shrink-0 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            <span>@lang('shop::app.checkout.cart.summary.trust.returns')</span>
        </div>
        <div class="flex items-center gap-2 text-xs text-gray-500">
            <svg class="h-5 w-5 shrink-0 text-yellow-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
            </svg>
            <span>@lang('shop::app.checkout.cart.summary.trust.guarantee')</span>
        </div>
        <div class="flex items-center gap-2 text-xs text-gray-500">
            <svg class="h-5 w-5 shrink-0 text-purple-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            <span>@lang('shop::app.checkout.cart.summary.trust.delivery')</span>
        </div>
    </div>
</div>

@pushOnce('scripts')
<script>
function giftCardWidget() {
    return {
        code: '',
        applied: false,
        appliedCode: '',
        discount: 0,
        message: '',
        get formattedDiscount() { return parseFloat(this.discount).toFixed(2); },
        init() {
            const stored = sessionStorage.getItem('gc_applied');
            if (stored) {
                const d = JSON.parse(stored);
                this.applied = true; this.appliedCode = d.code; this.discount = d.discount;
            }
        },
        apply() {
            this.message = '';
            fetch('{{ route("shop.gift-card.apply") }}', {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json'},
                body: JSON.stringify({code: this.code})
            }).then(r => r.json()).then(d => {
                if (d.success) {
                    this.applied = true; this.appliedCode = this.code; this.discount = d.discount;
                    sessionStorage.setItem('gc_applied', JSON.stringify({code: this.code, discount: d.discount}));
                    location.reload();
                } else {
                    this.message = d.message;
                }
            }).catch(() => { this.message = 'Something went wrong.'; });
        },
        remove() {
            fetch('{{ route("shop.gift-card.remove") }}', {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json'}
            }).then(() => { this.applied = false; this.appliedCode = ''; this.discount = 0; sessionStorage.removeItem('gc_applied'); location.reload(); });
        }
    };
}
</script>
@endPushOnce

@auth('customer')
@pushOnce('scripts')
<script>
function loyaltyWidget() {
    return {
        points: '',
        applied: sessionStorage.getItem('loyalty_applied') === 'true',
        formattedDiscount: sessionStorage.getItem('loyalty_formatted') || '',
        applyPoints() {
            fetch('{{ route("shop.loyalty.apply") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ points: this.points })
            }).then(r => r.json()).then(d => {
                if (d.success) {
                    sessionStorage.setItem('loyalty_applied', 'true');
                    sessionStorage.setItem('loyalty_formatted', d.formatted_discount);
                    location.reload();
                } else {
                    alert(d.message || 'Could not apply points.');
                }
            });
        },
        removePoints() {
            fetch('{{ route("shop.loyalty.remove") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            }).then(() => {
                sessionStorage.removeItem('loyalty_applied');
                sessionStorage.removeItem('loyalty_formatted');
                location.reload();
            });
        }
    };
}
</script>
@endPushOnce
@endauth

@auth('customer')
@pushOnce('scripts')
<script>
function walletWidget() {
    return {
        balance: 0,
        applied: 0,
        get formattedBalance() { return parseFloat(this.balance).toFixed(2); },
        get formattedApplied()  { return parseFloat(this.applied).toFixed(2); },
        init() {
            fetch('{{ route("shop.wallet.apply") }}', {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json'},
                body: JSON.stringify({amount: 0})
            }).then(r => r.json()).then(d => {
                if (d.balance !== undefined) this.balance = parseFloat(d.balance);
                if (d.applied  !== undefined) this.applied = parseFloat(d.applied);
            }).catch(() => {});
        },
        apply() {
            fetch('{{ route("shop.wallet.apply") }}', {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json'},
                body: JSON.stringify({amount: this.balance})
            }).then(r => r.json()).then(d => {
                if (d.applied > 0) { this.applied = parseFloat(d.applied); location.reload(); }
            });
        },
        remove() {
            fetch('{{ route("shop.wallet.remove") }}', {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json'}
            }).then(() => { this.applied = 0; location.reload(); });
        }
    };
}
</script>
@endPushOnce
@endauth
