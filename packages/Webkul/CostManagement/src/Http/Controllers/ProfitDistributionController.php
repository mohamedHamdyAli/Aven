<?php

namespace Webkul\CostManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Webkul\CostManagement\Models\ProfitDistribution;
use Webkul\CostManagement\Models\ProfitDistributionItem;
use Webkul\CostManagement\Models\Shareholder;

class ProfitDistributionController extends Controller
{
    public function index()
    {
        $distributions = ProfitDistribution::with('items.shareholder')
            ->orderByDesc('period_from')
            ->paginate(20);

        return view('cost_management::distributions.index', compact('distributions'));
    }

    public function create()
    {
        $shareholders = Shareholder::where('active', true)->orderByDesc('shares')->get();
        $totalShares  = $shareholders->sum('shares');
        $totalPct     = $totalShares > 0
            ? $shareholders->sum(fn ($s) => round(($s->shares / $totalShares) * 100, 4))
            : 0;

        // Pre-fill period: last full month
        $from = now()->subMonth()->startOfMonth()->toDateString();
        $to   = now()->subMonth()->endOfMonth()->toDateString();

        return view('cost_management::distributions.create', compact('shareholders', 'totalPct', 'from', 'to'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'period_from' => 'required|date',
            'period_to'   => 'required|date|after_or_equal:period_from',
            'net_profit'  => 'required|numeric',
            'notes'       => 'nullable|string',
        ]);

        $shareholders = Shareholder::where('active', true)->get();

        if ($shareholders->isEmpty()) {
            return back()->withErrors(['shareholders' => 'No active shareholders found.']);
        }

        DB::transaction(function () use ($data, $shareholders) {
            $totalDistributed = 0;
            $items = [];

            // Compute ownership % from shares at distribution time
            $totalShares = $shareholders->sum('shares');

            foreach ($shareholders as $sh) {
                $pct    = $totalShares > 0 ? round(($sh->shares / $totalShares) * 100, 4) : $sh->percentage;
                $amount = round(($pct / 100) * $data['net_profit'], 2);
                $items[] = [
                    'shareholder_id' => $sh->id,
                    'percentage'     => $pct,
                    'amount'         => $amount,
                ];
                $totalDistributed += $amount;
            }

            $distribution = ProfitDistribution::create([
                'period_from'       => $data['period_from'],
                'period_to'         => $data['period_to'],
                'net_profit'        => $data['net_profit'],
                'total_distributed' => $totalDistributed,
                'notes'             => $data['notes'] ?? null,
            ]);

            foreach ($items as $item) {
                ProfitDistributionItem::create(['distribution_id' => $distribution->id] + $item);
            }
        });

        return redirect()->route('admin.cost_management.distributions.index')
            ->with('success', 'Profit distribution created successfully.');
    }

    public function show(ProfitDistribution $distribution)
    {
        $distribution->load('items.shareholder');

        return view('cost_management::distributions.show', compact('distribution'));
    }

    public function destroy(ProfitDistribution $distribution)
    {
        $distribution->delete();

        return back()->with('success', 'Distribution deleted.');
    }
}
