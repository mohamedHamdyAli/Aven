<?php

namespace Webkul\Valu\Payment;

use Webkul\Checkout\Facades\Cart;
use Webkul\Payment\Payment\Payment;

class Valu extends Payment
{
    protected $code = 'valu';

    public function getRedirectUrl(): string
    {
        return route('valu.redirect');
    }

    public function isAvailable(): bool
    {
        if (! parent::isAvailable()) {
            return false;
        }

        if (! $this->getConfigData('merchant_id')) {
            return false;
        }

        $minAmount = (float) ($this->getConfigData('min_amount') ?? 1000);
        $cart = Cart::getCart();

        if (! $cart) {
            return false;
        }

        return (float) $cart->grand_total >= $minAmount;
    }

    public function getTitle(): string
    {
        return $this->getConfigData('title') ?? 'Valu — Buy Now Pay Later (تقسيط)';
    }

    public function getImage(): ?string
    {
        return null;
    }
}
