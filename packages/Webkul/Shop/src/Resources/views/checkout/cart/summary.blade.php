<div class="w-[418px] max-w-full max-md:w-full">
    {!! view_render_event('bagisto.shop.checkout.cart.summary.title.before') !!}

    <p
        class="text-2xl font-medium max-md:text-base"
        role="heading"
        aria-level="1"
    >
        @lang('shop::app.checkout.cart.summary.cart-summary')
    </p>

    {!! view_render_event('bagisto.shop.checkout.cart.summary.title.after') !!}

    <!-- Cart Totals -->
    <div class="mt-6 grid gap-4 max-md:mt-2 max-md:gap-2.5">
        <!-- Estimate Tax and Shipping -->
        @if (core()->getConfigData('sales.checkout.shopping_cart.estimate_shipping'))
            <template v-if="cart.have_stockable_items">
                @include('shop::checkout.cart.summary.estimate-shipping')
            </template>
        @endif

        <!-- Sub Total -->
        {!! view_render_event('bagisto.shop.checkout.cart.summary.sub_total.before') !!}

        <template v-if="displayTax.subtotal == 'including_tax'">
            <div class="flex justify-between text-right">
                <p class="text-base max-sm:text-sm">
                    @lang('shop::app.checkout.cart.summary.sub-total')
                </p>

                <p class="text-base font-medium max-sm:text-sm">
                    @{{ cart.formatted_sub_total_incl_tax }}
                </p>
            </div>
        </template>

        <template v-else-if="displayTax.subtotal == 'both'">
            <div class="flex justify-between text-right">
                <p class="text-base max-sm:text-sm">
                    @lang('shop::app.checkout.cart.summary.sub-total')
                </p>

                <div>
                    <p class="text-base font-medium max-sm:text-sm">
                        @{{ cart.formatted_sub_total }}
                    </p>

                    <p class="text-xs italic text-gray-500 dark:text-gray-400">
                        @lang('shop::app.checkout.cart.summary.incl-tax') @{{ cart.formatted_sub_total_incl_tax }}
                    </p>
                </div>
            </div>
        </template>

        <template v-else>
            <div class="flex justify-between text-right">
                <p class="text-base max-sm:text-sm">
                    @lang('shop::app.checkout.cart.summary.sub-total')
                </p>

                <p class="text-base font-medium max-sm:text-sm">
                    @{{ cart.formatted_sub_total }}
                </p>
            </div>
        </template>

        {!! view_render_event('bagisto.shop.checkout.cart.summary.sub_total.after') !!}

        <!-- Discount -->
        {!! view_render_event('bagisto.shop.checkout.cart.summary.discount_amount.before') !!}

        <template v-if="cart.discount_amount && parseFloat(cart.discount_amount) > 0">
            <!-- Single Source: Simple line. -->
            <div
                class="flex justify-between text-right"
                v-if="parseFloat(cart.items_discount_amount || 0) <= 0 || parseFloat(cart.shipping_discount_amount || 0) <= 0"
            >
                <p class="text-base text-red-600 max-sm:text-sm">
                    @lang('shop::app.checkout.cart.summary.discount-amount')
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
                        @lang('shop::app.checkout.cart.summary.discount-amount')
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
                            @lang('shop::app.checkout.cart.summary.items-discount')
                        </p>

                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            - @{{ cart.formatted_items_discount_amount }}
                        </p>
                    </div>

                    <div class="flex justify-between gap-1 text-right">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            @lang('shop::app.checkout.cart.summary.shipping-discount')
                        </p>

                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            - @{{ cart.formatted_shipping_discount_amount }}
                        </p>
                    </div>
                </div>
            </div>
        </template>

        {!! view_render_event('bagisto.shop.checkout.cart.summary.discount_amount.after') !!}

        <!-- Apply Coupon -->
        {!! view_render_event('bagisto.shop.checkout.cart.summary.coupon.before') !!}

        @include('shop::checkout.coupon')

        {!! view_render_event('bagisto.shop.checkout.cart.summary.coupon.after') !!}

        <!-- Shipping Rates -->
        {!! view_render_event('bagisto.shop.checkout.onepage.summary.delivery_charges.before') !!}
        
        <template v-if="displayTax.shipping == 'including_tax'">
            <div class="flex justify-between text-right">
                <p class="text-base max-sm:text-sm">
                    @lang('shop::app.checkout.cart.summary.delivery-charges')
                </p>

                <p class="text-base font-medium max-sm:text-sm">
                    + @{{ cart.formatted_shipping_amount_incl_tax }}
                </p>
            </div>
        </template>

        <template v-else-if="displayTax.shipping == 'both'">
            <div class="flex justify-between text-right">
                <p class="text-base max-sm:text-sm">
                    @lang('shop::app.checkout.cart.summary.delivery-charges')
                </p>

                <div>
                    <p class="text-base font-medium max-sm:text-sm">
                        + @{{ cart.formatted_shipping_amount }}
                    </p>

                    <p class="text-xs italic text-gray-500 dark:text-gray-400">
                        @lang('shop::app.checkout.cart.summary.incl-tax') @{{ cart.formatted_shipping_amount_incl_tax }}
                    </p>
                </div>
            </div>
        </template>

        <template v-else>
            <div class="flex justify-between text-right">
                <p class="text-base max-sm:text-sm">
                    @lang('shop::app.checkout.cart.summary.delivery-charges')
                </p>

                <p class="text-base font-medium max-sm:text-sm">
                    + @{{ cart.formatted_shipping_amount }}
                </p>
            </div>
        </template>

        {!! view_render_event('bagisto.shop.checkout.onepage.summary.delivery_charges.after') !!}

        <!-- Taxes -->
        {!! view_render_event('bagisto.shop.checkout.cart.summary.tax.before') !!}

        <div
            class="flex justify-between text-right"
            v-if="! cart.tax_total"
        >
            <p class="text-base max-md:font-normal max-sm:text-sm">
                @lang('shop::app.checkout.cart.summary.tax')
            </p>

            <p class="text-lg font-semibold max-md:text-sm">
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
                    @lang('shop::app.checkout.cart.summary.tax')
                </p>

                <p class="flex items-center gap-1.5 text-base font-medium max-md:font-medium max-sm:text-sm">
                    <template v-if="displayTax.subtotal === 'including_tax'">
                        @{{ cart.formatted_tax_total }}

                        <span class="text-xs italic font-normal text-gray-500 dark:text-gray-400">
                            (@lang('shop::app.checkout.cart.summary.included'))
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

        {!! view_render_event('bagisto.shop.checkout.cart.summary.tax.after') !!}

        <!-- Cart Grand Total -->
        {!! view_render_event('bagisto.shop.checkout.cart.summary.grand_total.before') !!}

        <div class="flex justify-between text-right">
            <p class="text-lg font-semibold max-md:text-base">
                @lang('shop::app.checkout.cart.summary.grand-total')
            </p>

            <p class="text-lg font-semibold max-md:text-base">
                @{{ cart.formatted_grand_total }}
            </p>
        </div>

        {!! view_render_event('bagisto.shop.checkout.cart.summary.grand_total.after') !!}

        {{-- Loyalty Points Redeem Widget --}}
        @auth('customer')
            @if (core()->getConfigData('general.loyalty.settings.enabled'))
                <div id="loyalty-widget" class="rounded-xl border border-indigo-100 bg-indigo-50 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-indigo-800">
                                ⭐ @lang('shop::app.checkout.cart.summary.loyalty.title')
                            </p>
                            <p id="loyalty-balance-text" class="mt-0.5 text-xs text-indigo-600">
                                @lang('shop::app.checkout.cart.summary.loyalty.loading')
                            </p>
                        </div>
                        <button
                            id="loyalty-redeem-btn"
                            class="hidden rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-700"
                            onclick="redeemLoyaltyPoints()"
                        >
                            @lang('shop::app.checkout.cart.summary.loyalty.redeem')
                        </button>
                    </div>
                </div>
            @endif
        @endauth

        {!! view_render_event('bagisto.shop.checkout.cart.summary.proceed_to_checkout.before') !!}

        <a
            href="{{ route('shop.checkout.onepage.index') }}"
            class="primary-button mt-4 place-self-end rounded-2xl px-11 py-3 max-md:my-4 max-md:max-w-full max-md:rounded-lg max-md:py-3 max-md:text-sm max-sm:w-full max-sm:py-2"
        >
            @lang('shop::app.checkout.cart.summary.proceed-to-checkout')
        </a>

        {!! view_render_event('bagisto.shop.checkout.cart.summary.proceed_to_checkout.after') !!}

        <!-- Trust Badges -->
        <div class="mt-5 grid grid-cols-2 gap-x-3 gap-y-3 border-t border-gray-100 pt-5">
            <div class="flex items-center gap-2">
                <svg class="h-5 w-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <span class="text-xs text-gray-500">@lang('shop::app.checkout.cart.trust.secure')</span>
            </div>

            <div class="flex items-center gap-2">
                <svg class="h-5 w-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                <span class="text-xs text-gray-500">@lang('shop::app.checkout.cart.trust.returns')</span>
            </div>

            <div class="flex items-center gap-2">
                <svg class="h-5 w-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <span class="text-xs text-gray-500">@lang('shop::app.checkout.cart.trust.guarantee')</span>
            </div>

            <div class="flex items-center gap-2">
                <svg class="h-5 w-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                <span class="text-xs text-gray-500">@lang('shop::app.checkout.cart.trust.delivery')</span>
            </div>
        </div>
    </div>
</div>