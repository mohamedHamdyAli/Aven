<?php

namespace Webkul\CostManagement\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Webkul\CostManagement\Models\CostManagementSetting;
use Webkul\CostManagement\Models\FinancialTransaction;
use Webkul\CostManagement\Models\GeneralExpense;
use Webkul\CostManagement\Models\Shareholder;

class BalanceSheetController extends Controller
{
    private const ORDER_STATUSES = ['completed', 'processing', 'closed'];

    public function index()
    {
        $data = $this->buildBalanceSheet();
        return view('cost_management::balance-sheet.index', compact('data'));
    }

    private function buildBalanceSheet(): array
    {
        // ── ASSETS ───────────────────────────────────────────────────────────────

        // Inventory: qty on hand × unit cost (excl. shipping — period cost)
        $inventoryValue = (float) DB::table('product_inventories as pi')
            ->join('product_costs as pc', 'pc.product_id', '=', 'pi.product_id')
            ->join('products as p', 'p.id', '=', 'pi.product_id')
            ->where('p.type', '!=', 'configurable')
            ->selectRaw('SUM(pi.qty * (pc.cost_price + pc.manufacturing_fee + pc.other_costs)) as total')
            ->value('total') ?? 0;

        // Receivables: unpaid / in-progress orders
        $receivables = (float) DB::table('orders')
            ->whereIn('status', ['pending', 'pending_payment'])
            ->sum('grand_total');

        // ── EQUITY ───────────────────────────────────────────────────────────────

        $shareholders = Shareholder::with('contributions')->get();
        $totalShares  = $shareholders->sum('shares');
        $sharePrice   = (float) CostManagementSetting::get('share_price', 0);
        $shareCapital = round($totalShares * $sharePrice, 2);

        $totalContributed = (float) DB::table('capital_contributions')->sum('amount');
        $totalDistributed = (float) DB::table('profit_distribution_items')->sum('amount');

        // All-time retained earnings (cumulative net profit before distributions)
        $retainedEarnings = $this->calcAllTimeNetProfit();

        // Net equity = share capital + contributions + retained earnings − distributions
        $totalEquity = $shareCapital + $totalContributed + $retainedEarnings - $totalDistributed;

        // ── PER-SHAREHOLDER equity breakdown ────────────────────────────────────
        $shareholderRows = $shareholders->map(function ($sh) use ($totalShares, $sharePrice, $retainedEarnings, $totalDistributed) {
            $pct            = $totalShares > 0 ? round(($sh->shares / $totalShares) * 100, 4) : 0;
            $shCapital      = round($sh->shares * $sharePrice, 2);
            $shContributed  = round($sh->totalContributed(), 2);
            $shEarnings     = round($retainedEarnings * ($pct / 100), 2);
            $shDistributed  = round($sh->totalEarned(), 2);
            $shNetEquity    = round($shCapital + $shContributed + $shEarnings - $shDistributed, 2);

            return [
                'name'          => $sh->name,
                'shares'        => $sh->shares,
                'pct'           => $pct,
                'share_capital' => $shCapital,
                'contributed'   => $shContributed,
                'earnings'      => $shEarnings,
                'distributed'   => $shDistributed,
                'net_equity'    => $shNetEquity,
                'active'        => $sh->active,
            ];
        })->sortByDesc('shares')->values()->toArray();

        return [
            // Assets
            'inventory_value' => round($inventoryValue, 2),
            'receivables'     => round($receivables, 2),
            'total_assets'    => round($inventoryValue + $receivables, 2),

            // Equity
            'share_capital'       => $shareCapital,
            'total_contributed'   => round($totalContributed, 2),
            'retained_earnings'   => round($retainedEarnings, 2),
            'total_distributed'   => round($totalDistributed, 2),
            'total_equity'        => round($totalEquity, 2),

            // Shareholders
            'total_shares'    => $totalShares,
            'share_price'     => $sharePrice,
            'shareholders'    => $shareholderRows,

            'as_of' => now()->format('d M Y'),
        ];
    }

    private function calcAllTimeNetProfit(): float
    {
        // Revenue
        $ordersAgg = DB::table('orders')
            ->whereIn('status', self::ORDER_STATUSES)
            ->selectRaw('SUM(grand_total) as revenue, SUM(grand_total_refunded) as refunds')
            ->first();

        $revenue = (float) ($ordersAgg->revenue ?? 0);
        $refunds = (float) ($ordersAgg->refunds ?? 0);
        $net     = $revenue - $refunds;

        // COGS
        $cogsRow = DB::table('order_items as oi')
            ->join('orders as o', 'o.id', '=', 'oi.order_id')
            ->join('product_costs as pc', 'pc.product_id', '=', 'oi.product_id')
            ->whereIn('o.status', self::ORDER_STATUSES)
            ->selectRaw('SUM(oi.qty_ordered * (pc.cost_price + pc.manufacturing_fee + pc.shipping_cost_per_unit + pc.other_costs)) as cogs')
            ->first();

        $cogs = (float) ($cogsRow->cogs ?? 0);

        // Operating expenses
        $expenses = (float) GeneralExpense::sum('amount');
        $adSpend  = (float) abs(FinancialTransaction::where('type', 'ad_spend')->sum('amount'));

        return round($net - $cogs - $expenses - $adSpend, 2);
    }
}
