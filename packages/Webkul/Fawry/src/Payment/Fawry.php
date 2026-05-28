<?php

namespace Webkul\Fawry\Payment;

use Webkul\Payment\Payment\Payment;

class Fawry extends Payment
{
    protected $code = 'fawry';

    public function isAvailable(): bool
    {
        return (bool) $this->getConfigData('active')
            && $this->getConfigData('merchant_code')
            && $this->getConfigData('security_key');
    }

    public function getRedirectUrl(): string
    {
        return route('fawry.redirect');
    }

    public function getTitle(): string
    {
        return $this->getConfigData('title') ?? 'Fawry';
    }

    public function getImage()
    {
        return null;
    }
}
