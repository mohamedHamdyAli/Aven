<?php

namespace Webkul\CostManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\CostManagement\Models\ProfitDistributionItem;
use Webkul\CostManagement\Models\Shareholder;

class ShareholderPortalController extends Controller
{
    public function showLogin()
    {
        if (session('shareholder_portal_id')) {
            return redirect()->route('shareholder.portal.dashboard');
        }

        return view('cost_management::shareholder-portal.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'phone' => 'required|string',
        ]);

        $shareholder = Shareholder::where('email', $request->email)
            ->where('phone', $request->phone)
            ->where('active', true)
            ->first();

        if (! $shareholder) {
            return back()
                ->withErrors(['credentials' => 'البيانات غير صحيحة. تأكد من الإيميل ورقم التليفون.'])
                ->withInput();
        }

        session(['shareholder_portal_id' => $shareholder->id]);

        return redirect()->route('shareholder.portal.dashboard');
    }

    public function dashboard()
    {
        $id = session('shareholder_portal_id');

        if (! $id) {
            return redirect()->route('shareholder.portal.login');
        }

        $shareholder = Shareholder::find($id);

        if (! $shareholder) {
            session()->forget('shareholder_portal_id');
            return redirect()->route('shareholder.portal.login');
        }

        $items = ProfitDistributionItem::with('distribution')
            ->where('shareholder_id', $shareholder->id)
            ->orderByDesc('created_at')
            ->get();

        $totalEarned     = $items->sum('amount');
        $bestDistribution = $items->sortByDesc('amount')->first();
        $lastDistribution = $items->sortByDesc(fn ($i) => $i->distribution?->period_to)->first();

        return view('cost_management::shareholder-portal.dashboard', compact(
            'shareholder', 'items', 'totalEarned', 'bestDistribution', 'lastDistribution'
        ));
    }

    public function logout()
    {
        session()->forget('shareholder_portal_id');

        return redirect()->route('shareholder.portal.login');
    }
}
