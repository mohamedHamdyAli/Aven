<?php

namespace Webkul\Affiliate\Http\Controllers\Shop;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\Affiliate\Models\Affiliate;
use Webkul\Affiliate\Models\AffiliateClick;

class AffiliateController extends Controller
{
    public function track(Request $request, string $code)
    {
        $affiliate = Affiliate::where('code', $code)->where('status', 'approved')->first();

        if ($affiliate) {
            AffiliateClick::create([
                'affiliate_id' => $affiliate->id,
                'ip'           => $request->ip(),
                'url'          => url()->previous(),
            ]);

            session(['affiliate_code' => $code]);
            cookie()->queue('affiliate_code', $code, 60 * 24 * 30);
        }

        $redirect = $request->query('redirect', '/');

        return redirect($redirect);
    }

    public function register()
    {
        $customer = auth()->guard('customer')->user();

        return view('affiliate::shop.register', compact('customer'));
    }

    public function apply(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'required|email|unique:affiliates,email',
            'notes' => 'nullable|string|max:500',
        ]);

        $customerId = auth()->guard('customer')->id();

        if ($customerId && Affiliate::where('customer_id', $customerId)->exists()) {
            return back()->withErrors(['email' => 'You already have an affiliate account.']);
        }

        Affiliate::create([
            'customer_id' => $customerId,
            'name'        => $data['name'],
            'email'       => $data['email'],
            'code'        => Affiliate::generateCode(),
            'notes'       => $data['notes'] ?? null,
            'status'      => 'pending',
        ]);

        session()->flash('success', 'Application submitted! You will be notified once approved.');

        return redirect()->route('shop.affiliate.dashboard');
    }

    public function dashboard()
    {
        $customer  = auth()->guard('customer')->user();
        $affiliate = Affiliate::where('customer_id', $customer->id)->first();

        if (! $affiliate) {
            return redirect()->route('shop.affiliate.register');
        }

        $commissions = $affiliate->commissions()->latest()->paginate(10);

        return view('affiliate::shop.dashboard', compact('affiliate', 'commissions'));
    }
}
