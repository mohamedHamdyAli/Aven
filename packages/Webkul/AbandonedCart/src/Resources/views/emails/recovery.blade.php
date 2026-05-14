<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ core()->getCurrentLocale()->direction ?? 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ trans('abandoned-cart::app.emails.recovery.title') }}</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 0 auto; background: #ffffff; }
        .header { background-color: #0a3d62; padding: 24px 32px; text-align: center; }
        .header h1 { color: #ffffff; font-size: 22px; margin: 0; }
        .body { padding: 32px; color: #333333; }
        .body h2 { font-size: 18px; margin-top: 0; }
        .item-row { display: flex; align-items: center; border-bottom: 1px solid #eeeeee; padding: 12px 0; }
        .item-row img { width: 64px; height: 64px; object-fit: cover; border-radius: 4px; margin-right: 16px; }
        .item-info { flex: 1; }
        .item-info .name { font-weight: bold; }
        .item-info .price { color: #888888; font-size: 13px; }
        .totals { margin-top: 20px; text-align: right; font-size: 14px; }
        .totals .grand-total { font-size: 18px; font-weight: bold; color: #0a3d62; }
        .cta { text-align: center; margin: 32px 0; }
        .cta a { background-color: #e84118; color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 4px; font-size: 16px; font-weight: bold; display: inline-block; }
        .footer { background-color: #f9f9f9; padding: 20px 32px; text-align: center; font-size: 12px; color: #999999; border-top: 1px solid #eeeeee; }
        .footer a { color: #999999; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>{{ core()->getConfigData('general.store_information.name') ?? config('app.name') }}</h1>
    </div>

    <div class="body">
        <h2>
            @if ($attempt === 1)
                {{ trans('abandoned-cart::app.emails.recovery.greeting-1', ['name' => $cart->customer_first_name ?? trans('abandoned-cart::app.emails.recovery.there')]) }}
            @elseif ($attempt === 2)
                {{ trans('abandoned-cart::app.emails.recovery.greeting-2', ['name' => $cart->customer_first_name ?? trans('abandoned-cart::app.emails.recovery.there')]) }}
            @else
                {{ trans('abandoned-cart::app.emails.recovery.greeting-3', ['name' => $cart->customer_first_name ?? trans('abandoned-cart::app.emails.recovery.there')]) }}
            @endif
        </h2>

        <p>{{ trans('abandoned-cart::app.emails.recovery.body') }}</p>

        {{-- Cart items --}}
        @foreach ($cart->items as $item)
            <div class="item-row">
                @php
                    $image = $item->product?->images?->first();
                @endphp

                @if ($image)
                    <img src="{{ Storage::url($image->path) }}" alt="{{ $item->name }}">
                @else
                    <img src="{{ asset('vendor/webkul/ui/assets/images/product/large-product-placeholder.png') }}" alt="{{ $item->name }}">
                @endif

                <div class="item-info">
                    <div class="name">{{ $item->name }}</div>
                    <div class="price">
                        {{ trans('abandoned-cart::app.emails.recovery.qty') }}: {{ $item->quantity }}
                        &nbsp;&bull;&nbsp;
                        {{ core()->formatBasePrice($item->base_total) }}
                    </div>
                </div>
            </div>
        @endforeach

        <div class="totals">
            <div>{{ trans('abandoned-cart::app.emails.recovery.subtotal') }}: {{ core()->formatBasePrice($cart->base_sub_total) }}</div>
            @if ($cart->base_tax_total > 0)
                <div>{{ trans('abandoned-cart::app.emails.recovery.tax') }}: {{ core()->formatBasePrice($cart->base_tax_total) }}</div>
            @endif
            <div class="grand-total">{{ trans('abandoned-cart::app.emails.recovery.total') }}: {{ core()->formatBasePrice($cart->base_grand_total) }}</div>
        </div>

        <div class="cta">
            <a href="{{ URL::signedRoute('shop.abandoned-cart.recover', ['token' => $cart->notification_token], now()->addDays(7)) }}">
                {{ trans('abandoned-cart::app.emails.recovery.cta') }}
            </a>
        </div>

        <p style="font-size: 13px; color: #888;">
            {{ trans('abandoned-cart::app.emails.recovery.privacy-note') }}
            <a href="{{ route('shop.abandoned-cart.unsubscribe', ['token' => $cart->notification_token]) }}">
                {{ trans('abandoned-cart::app.emails.recovery.unsubscribe') }}
            </a>
        </p>
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} {{ core()->getConfigData('general.store_information.name') ?? config('app.name') }}.
        {{ trans('abandoned-cart::app.emails.recovery.footer') }}
    </div>
</div>
</body>
</html>
