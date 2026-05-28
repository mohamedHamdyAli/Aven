<?php

namespace Webkul\Affiliate\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\Affiliate\Models\Affiliate;
use Webkul\Affiliate\Models\AffiliateCommission;

class AffiliateController extends Controller
{
    public function index()
    {
        $affiliates = Affiliate::withCount('clicks')
            ->withSum(['commissions as pending_commission' => fn($q) => $q->where('status', 'approved')], 'commission')
            ->latest()
            ->paginate(25);

        return view('affiliate::admin.index', compact('affiliates'));
    }

    public function show(int $id)
    {
        $affiliate   = Affiliate::findOrFail($id);
        $commissions = $affiliate->commissions()->latest()->paginate(20);

        return view('affiliate::admin.show', compact('affiliate', 'commissions'));
    }

    public function approve(int $id)
    {
        Affiliate::findOrFail($id)->update(['status' => 'approved']);
        session()->flash('success', 'Affiliate approved.');
        return back();
    }

    public function reject(int $id)
    {
        Affiliate::findOrFail($id)->update(['status' => 'rejected']);
        session()->flash('success', 'Affiliate rejected.');
        return back();
    }

    public function markPaid(Request $request)
    {
        $data = $request->validate([
            'affiliate_id' => 'required|exists:affiliates,id',
            'amount'       => 'required|numeric|min:0.01',
        ]);

        AffiliateCommission::where('affiliate_id', $data['affiliate_id'])
            ->where('status', 'approved')
            ->update(['status' => 'paid']);

        Affiliate::findOrFail($data['affiliate_id'])->increment('total_paid', $data['amount']);

        session()->flash('success', 'Payment recorded.');

        return back();
    }

    public function approveCommission(int $id)
    {
        AffiliateCommission::findOrFail($id)->update(['status' => 'approved']);

        return response()->json(['ok' => true]);
    }
}
