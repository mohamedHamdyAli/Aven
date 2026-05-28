<?php

namespace Webkul\Referral\Http\Controllers\Shop;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Webkul\Referral\Models\CustomerReferral;
use Webkul\Referral\Services\ReferralService;

class ReferralController extends Controller
{
    public function __construct(private ReferralService $service) {}

    public function track(string $code)
    {
        if ($this->service->trackVisit($code)) {
            session(['referral_code' => $code]);
        }

        return redirect('/')->with('info', 'Welcome! You\'ll receive a reward on your first purchase.');
    }

    public function dashboard()
    {
        $customer = auth()->guard('customer')->user();
        $code = $this->service->getOrCreateCode($customer->id);
        $shareUrl = $this->service->getShareUrl($code);

        $referral = CustomerReferral::where('customer_id', $customer->id)->first();

        $conversions = DB::table('referral_conversions')
            ->where('referrer_customer_id', $customer->id)
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        return view('referral::shop.dashboard', compact('code', 'shareUrl', 'referral', 'conversions'));
    }
}
