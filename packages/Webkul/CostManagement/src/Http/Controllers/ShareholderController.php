<?php

namespace Webkul\CostManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\CostManagement\Models\CostManagementSetting;
use Webkul\CostManagement\Models\Shareholder;

class ShareholderController extends Controller
{
    public function index()
    {
        $shareholders = Shareholder::with('contributions')->orderByDesc('shares')->get();
        $totalShares  = $shareholders->sum('shares');
        $sharePrice   = (float) CostManagementSetting::get('share_price', 0);
        $totalCapital = $totalShares * $sharePrice;

        return view('cost_management::shareholders.index', compact(
            'shareholders', 'totalShares', 'sharePrice', 'totalCapital'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:191',
            'email'     => 'nullable|email|max:191',
            'phone'     => 'nullable|string|max:30',
            'shares'    => 'required|integer|min:1',
            'joined_at' => 'nullable|date',
            'notes'     => 'nullable|string',
        ]);

        Shareholder::create($data + ['active' => true, 'percentage' => 0]);
        $this->recomputePercentages();

        return back()->with('success', 'Shareholder added successfully.');
    }

    public function update(Request $request, Shareholder $shareholder)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:191',
            'email'     => 'nullable|email|max:191',
            'phone'     => 'nullable|string|max:30',
            'shares'    => 'required|integer|min:1',
            'joined_at' => 'nullable|date',
            'active'    => 'boolean',
            'notes'     => 'nullable|string',
        ]);

        $shareholder->update($data);
        $this->recomputePercentages();

        return back()->with('success', 'Shareholder updated.');
    }

    public function destroy(Shareholder $shareholder)
    {
        $shareholder->delete();
        $this->recomputePercentages();

        return back()->with('success', 'Shareholder removed.');
    }

    public function updateSettings(Request $request)
    {
        $request->validate(['share_price' => 'required|numeric|min:0']);
        CostManagementSetting::set('share_price', $request->input('share_price'));

        return back()->with('success', 'Share price updated.');
    }

    private function recomputePercentages(): void
    {
        $all         = Shareholder::all();
        $totalShares = $all->sum('shares');

        foreach ($all as $sh) {
            $pct = $totalShares > 0 ? round(($sh->shares / $totalShares) * 100, 4) : 0;
            Shareholder::where('id', $sh->id)->update(['percentage' => $pct]);
        }
    }
}
