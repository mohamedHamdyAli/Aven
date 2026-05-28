<?php

namespace Webkul\Aramex\Carriers;

use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Shipping\Carriers\AbstractShipping;

class Aramex extends AbstractShipping
{
    protected $code = 'aramex';

    /**
     * Greater Cairo governorate codes — billed at the cairo_rate.
     */
    private const CAIRO_ZONE = ['cairo', 'giza', 'qalyubia', 'al-qalyubia'];

    public function calculate(): CartShippingRate|false
    {
        if (! $this->isAvailable()) {
            return false;
        }

        if (! $this->getConfigData('api_username')) {
            return false;
        }

        $cart    = Cart::getCart();
        $address = $cart?->shipping_address;

        if (! $address) {
            return false;
        }

        $stateCode = strtolower(trim($address->state ?? ''));
        $isGreaterCairo = in_array($stateCode, self::CAIRO_ZONE, true);

        $basePrice = $isGreaterCairo
            ? (float) ($this->getConfigData('cairo_rate') ?? 40)
            : (float) ($this->getConfigData('other_rate') ?? 65);

        $zone = $isGreaterCairo ? 'Greater Cairo' : 'Outside Cairo';

        $rate                     = new CartShippingRate;
        $rate->carrier            = $this->getCode();
        $rate->carrier_title      = $this->getTitle() ?? 'Aramex';
        $rate->method             = $this->getMethod();
        $rate->method_title       = 'Aramex — '.$zone;
        $rate->method_description = $this->getDescription() ?? 'Delivery via Aramex';
        $rate->price              = core()->convertPrice($basePrice);
        $rate->base_price         = $basePrice;

        return $rate;
    }
}
