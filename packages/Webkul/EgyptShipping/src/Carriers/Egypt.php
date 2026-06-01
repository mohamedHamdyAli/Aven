<?php

namespace Webkul\EgyptShipping\Carriers;

use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\EgyptShipping\Models\EgyptGovernorate;
use Webkul\Shipping\Carriers\AbstractShipping;

class Egypt extends AbstractShipping
{
    protected $code = 'egypt';

    public function calculate(): CartShippingRate|false
    {
        if (! $this->isAvailable()) {
            return false;
        }

        $cart = Cart::getCart();
        $address = $cart?->shipping_address;

        $code = $address->state ?: $address->city ?? '';

        if (! $address || empty($code)) {
            return false;
        }

        $governorate = EgyptGovernorate::where('code', $code)
            ->where('is_active', true)
            ->whereNotNull('rate')
            ->first();

        if (! $governorate) {
            return false;
        }

        $rate = new CartShippingRate;
        $rate->carrier             = $this->getCode();
        $rate->carrier_title       = $this->getTitle() ?? 'Egypt Shipping';
        $rate->method              = $this->getMethod();
        $rate->method_title        = $governorate->name_ar.' - '.$governorate->name_en;
        $rate->method_description  = $this->getDescription();
        $rate->price               = core()->convertPrice($governorate->rate);
        $rate->base_price          = (float) $governorate->rate;

        return $rate;
    }
}
