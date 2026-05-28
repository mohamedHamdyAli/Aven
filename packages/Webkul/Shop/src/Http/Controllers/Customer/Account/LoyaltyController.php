<?php

namespace Webkul\Shop\Http\Controllers\Customer\Account;

use Webkul\Customer\Services\LoyaltyService;
use Webkul\Customer\Services\ReferralService;
use Webkul\Shop\Http\Controllers\Controller;

class LoyaltyController extends Controller
{
    public function __construct(
        protected LoyaltyService  $loyalty,
        protected ReferralService $referral,
    ) {}

    public function loyaltyIndex()
    {
        $customer     = auth()->guard('customer')->user();
        $balance      = $this->loyalty->getBalance($customer->id);
        $transactions = $this->loyalty->getTransactions($customer->id);

        return view('shop::customers.account.loyalty.index', compact('balance', 'transactions'));
    }

    public function referralIndex()
    {
        $customer = auth()->guard('customer')->user();
        $stats    = $this->referral->getReferralStats($customer->id);

        return view('shop::customers.account.referral.index', compact('stats'));
    }
}
