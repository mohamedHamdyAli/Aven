@if (core()->getConfigData('general.content.google_analytics.enabled') && core()->getConfigData('general.content.google_analytics.measurement_id'))
    @push('scripts')
        <script>
            window.addEventListener('load', function () {
                if (typeof gtag === 'undefined') return;
                gtag('event', 'purchase', {
                    transaction_id: '{{ $order->increment_id }}',
                    value:          {{ (float) $order->grand_total }},
                    currency:       '{{ $order->order_currency_code }}',
                    items: [
                        @foreach ($order->items as $item)
                        { item_id: '{{ $item->sku }}', item_name: @json($item->name), price: {{ (float) $item->price }}, quantity: {{ (int) $item->qty_ordered }} },
                        @endforeach
                    ],
                });
            });
        </script>
    @endpush
@endif

@if (core()->getConfigData('general.content.facebook_pixel.enabled') && core()->getConfigData('general.content.facebook_pixel.pixel_id'))
    @push('scripts')
        <script>
            window.addEventListener('load', function () {
                if (typeof fbq === 'undefined') return;
                fbq('track', 'Purchase', {
                    value:        {{ (float) $order->grand_total }},
                    currency:     '{{ $order->order_currency_code }}',
                    content_type: 'product',
                    content_ids:  [{{ $order->items->pluck('sku')->map(fn($s) => "'$s'")->implode(',') }}],
                    num_items:    {{ $order->items->sum('qty_ordered') }},
                });
            });
        </script>
    @endpush
@endif

<x-shop::layouts
	:has-header="true"
	:has-feature="false"
	:has-footer="true"
>
    <!-- Page Title -->
    <x-slot:title>
		@lang('shop::app.checkout.success.thanks')
    </x-slot>

	<!-- Page content -->
	<div class="container mt-8 px-[60px] max-lg:px-8">
		<div class="grid place-items-center gap-y-5 max-md:gap-y-2.5">
			{{ view_render_event('bagisto.shop.checkout.success.image.before', ['order' => $order]) }}

			<img
				class="max-md:h-[100px] max-md:w-[100px]"
				src="{{ bagisto_asset('images/thank-you.png') }}"
				alt="@lang('shop::app.checkout.success.thanks')"
				title="@lang('shop::app.checkout.success.thanks')"
                loading="lazy"
                decoding="async"
			>

			{{ view_render_event('bagisto.shop.checkout.success.image.after', ['order' => $order]) }}

			<p class="text-xl max-md:text-sm">
				@if (auth()->guard('customer')->user())
					@lang('shop::app.checkout.success.order-id-info', [
						'order_id' => '<a class="text-blue-700" href="'.route('shop.customers.account.orders.view', $order->id).'">'.$order->increment_id.'</a>'
					])
				@else
					@lang('shop::app.checkout.success.order-id-info', ['order_id' => $order->increment_id])
				@endif
			</p>

			<p class="font-medium md:text-2xl">
				@lang('shop::app.checkout.success.thanks')
			</p>

			<p class="text-xl text-zinc-500 max-md:text-center max-md:text-xs">
				@if (! empty($order->checkout_message))
					{!! nl2br($order->checkout_message) !!}
				@else
					@lang('shop::app.checkout.success.info')
				@endif
			</p>

			{{ view_render_event('bagisto.shop.checkout.success.continue-shopping.before', ['order' => $order]) }}

			<a href="{{ route('shop.home.index') }}">
				<div class="w-max cursor-pointer rounded-2xl bg-navyBlue px-11 py-3 text-center text-base font-medium text-white max-md:rounded-lg max-md:px-6 max-md:py-1.5">
             		@lang('shop::app.checkout.cart.index.continue-shopping')
				</div>
			</a>

			{{ view_render_event('bagisto.shop.checkout.success.continue-shopping.after', ['order' => $order]) }}
		</div>
	</div>

	{{-- Post-Purchase Upsell --}}
	@php
		$orderedProductIds = $order->items->pluck('product_id')->unique()->toArray();
		$upsellProducts = collect();
		foreach ($orderedProductIds as $pid) {
			$op = app(\Webkul\Product\Repositories\ProductRepository::class)->find($pid);
			if ($op) {
				$upsellProducts = $upsellProducts->merge(
					$op->up_sells()->where('products.status', 1)->get()
				);
				if ($upsellProducts->count() < 4) {
					$upsellProducts = $upsellProducts->merge(
						$op->related_products()->where('products.status', 1)->get()
					);
				}
			}
		}
		$upsellProducts = $upsellProducts
			->unique('id')
			->filter(fn($p) => ! in_array($p->id, $orderedProductIds))
			->take(4);
	@endphp

	@if ($upsellProducts->isNotEmpty())
		<div class="container mt-12 px-[60px] pb-12 max-lg:px-8 max-md:px-4">
			<h2 class="mb-6 text-center text-2xl font-bold text-gray-800 max-md:text-xl">
				@lang('shop::app.checkout.success.you-may-also-like')
			</h2>

			<div class="grid grid-cols-4 gap-5 max-lg:grid-cols-3 max-md:grid-cols-2 max-sm:grid-cols-2">
				@foreach ($upsellProducts as $upsellProduct)
					@php
						$imgData = \Webkul\Product\ProductImage::getProductBaseImage($upsellProduct);
						$imgUrl  = $imgData['medium_image_url'] ?? bagisto_asset('images/product-placeholder.webp');
						$minPrice = $upsellProduct->getTypeInstance()->getMinimalPrice();
					@endphp

					<a
						href="{{ route('shop.product_or_category.index', $upsellProduct->url_key) }}"
						class="group flex flex-col overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm transition-shadow hover:shadow-md"
					>
						<div class="overflow-hidden bg-zinc-100">
							<img
								src="{{ $imgUrl }}"
								alt="{{ $upsellProduct->name }}"
								class="h-48 w-full object-cover transition-transform duration-300 group-hover:scale-105 max-sm:h-36"
								loading="lazy"
							>
						</div>

						<div class="flex flex-1 flex-col gap-1.5 p-3">
							<p class="line-clamp-2 text-sm font-medium text-gray-800">{{ $upsellProduct->name }}</p>
							<p class="text-base font-bold text-navyBlue">{{ core()->formatPrice($minPrice) }}</p>
						</div>
					</a>
				@endforeach
			</div>
		</div>
	@endif
</x-shop::layouts>
