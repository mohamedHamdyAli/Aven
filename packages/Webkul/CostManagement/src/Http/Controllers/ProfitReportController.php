<?php

namespace Webkul\CostManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Webkul\CostManagement\Models\CostManagementSetting;
use Webkul\CostManagement\Models\FinancialTransaction;
use Webkul\CostManagement\Models\GeneralExpense;
use Webkul\CostManagement\Models\Shareholder;

class ProfitReportController extends Controller
{
    private const ORDER_STATUSES = ['completed', 'processing', 'closed'];

    public function index(Request $request)
    {
        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to   = $request->input('to', now()->endOfMonth()->toDateString());

        $platforms = FinancialTransaction::platforms();

        return view('cost_management::report.index', compact('platforms', 'from', 'to'));
    }

    public function data(Request $request)
    {
        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to   = $request->input('to', now()->endOfMonth()->toDateString());

        $cacheKey = "pl_data_{$from}_{$to}";

        $payload = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($from, $to) {
            return [
                'stats'        => $this->calcStats($from, $to),
                'monthly'      => $this->monthlyChart(),
                'topProducts'  => $this->topProducts($from, $to),
                'transactions' => $this->recentTransactions($from, $to),
                'shareholders' => $this->shareholderStats(),
            ];
        });

        return response()->json($payload);
    }

    private function calcStats(string $from, string $to): array
    {
        $dateRange = [$from.' 00:00:00', $to.' 23:59:59'];

        // ── Revenue from orders ──────────────────────────────────────────────────
        $ordersAgg = DB::table('orders')
            ->whereIn('status', self::ORDER_STATUSES)
            ->whereBetween('created_at', $dateRange)
            ->selectRaw('
                COUNT(*) as cnt,
                SUM(grand_total) as revenue,
                SUM(shipping_amount) as shipping_collected,
                SUM(grand_total_refunded) as refunds
            ')
            ->first();

        $revenue           = (float) ($ordersAgg->revenue ?? 0);
        $shippingCollected = (float) ($ordersAgg->shipping_collected ?? 0);
        $refunds           = (float) ($ordersAgg->refunds ?? 0);
        $ordersCount       = (int)   ($ordersAgg->cnt ?? 0);
        $netRevenue        = $revenue - $refunds;

        // ── COGS (split product vs shipping) ────────────────────────────────────
        $cogsRow = DB::table('order_items as oi')
            ->join('orders as o', 'o.id', '=', 'oi.order_id')
            ->join('product_costs as pc', 'pc.product_id', '=', 'oi.product_id')
            ->whereIn('o.status', self::ORDER_STATUSES)
            ->whereBetween('o.created_at', $dateRange)
            ->selectRaw('
                SUM(oi.qty_ordered * (pc.cost_price + pc.manufacturing_fee + pc.other_costs)) as product_cogs,
                SUM(oi.qty_ordered * pc.shipping_cost_per_unit) as shipping_cogs
            ')
            ->first();

        $productCogs  = (float) ($cogsRow->product_cogs ?? 0);
        $shippingCogs = (float) ($cogsRow->shipping_cogs ?? 0);
        $totalCogs    = $productCogs + $shippingCogs;

        // ── Operating expenses ───────────────────────────────────────────────────
        $expenses = (float) GeneralExpense::whereBetween('expense_date', [$from, $to])->sum('amount');
        $adSpend  = (float) abs(FinancialTransaction::where('type', 'ad_spend')
            ->whereBetween('transaction_date', [$from, $to])
            ->sum('amount'));

        // ── Capital & shareholder movements ─────────────────────────────────────
        $capitalReceived = (float) DB::table('capital_contributions')
            ->whereBetween('contributed_at', [$from, $to])
            ->sum('amount');

        $distributionsPaid = (float) DB::table('profit_distribution_items as pdi')
            ->join('profit_distributions as pd', 'pd.id', '=', 'pdi.distribution_id')
            ->whereBetween('pd.period_from', [$from, $to])
            ->sum('pdi.amount');

        // ── Calculated lines ─────────────────────────────────────────────────────
        $grossProfit  = $netRevenue - $totalCogs;
        $netProfit    = $grossProfit - $expenses - $adSpend;
        $shippingNet  = $shippingCollected - $shippingCogs;

        return [
            'revenue'            => round($revenue, 2),
            'shipping_collected' => round($shippingCollected, 2),
            'refunds'            => round($refunds, 2),
            'net_revenue'        => round($netRevenue, 2),
            'product_cogs'       => round($productCogs, 2),
            'shipping_cogs'      => round($shippingCogs, 2),
            'cogs'               => round($totalCogs, 2),
            'gross_profit'       => round($grossProfit, 2),
            'expenses'           => round($expenses, 2),
            'ad_spend'           => round($adSpend, 2),
            'net_profit'         => round($netProfit, 2),
            'shipping_net'       => round($shippingNet, 2),
            'capital_received'   => round($capitalReceived, 2),
            'distributions_paid' => round($distributionsPaid, 2),
            'margin'             => $netRevenue > 0 ? round(($netProfit / $netRevenue) * 100, 1) : 0,
            'orders_count'       => $ordersCount,
        ];
    }

    private function shareholderStats(): array
    {
        $shareholders  = Shareholder::with('contributions')->get();
        $totalShares   = $shareholders->sum('shares');
        $sharePrice    = (float) CostManagementSetting::get('share_price', 0);
        $totalCapital  = $totalShares * $sharePrice;
        $totalContributed = (float) DB::table('capital_contributions')->sum('amount');
        $totalDistributed = (float) DB::table('profit_distribution_items')->sum('amount');

        return [
            'total_shares'      => $totalShares,
            'share_price'       => $sharePrice,
            'total_capital'     => round($totalCapital, 2),
            'total_contributed' => round($totalContributed, 2),
            'total_distributed' => round($totalDistributed, 2),
            'shareholders'      => $shareholders->map(function ($s) use ($totalShares, $sharePrice) {
                return [
                    'name'        => $s->name,
                    'shares'      => $s->shares,
                    'pct'         => $totalShares > 0 ? round(($s->shares / $totalShares) * 100, 2) : 0,
                    'investment'  => round($s->shares * $sharePrice, 2),
                    'contributed' => round($s->totalContributed(), 2),
                    'earned'      => round($s->totalEarned(), 2),
                ];
            })->values()->toArray(),
        ];
    }

    private function monthlyChart(): array
    {
        $start = now()->subMonths(11)->startOfMonth()->toDateString();
        $end   = now()->endOfMonth()->toDateString();

        $revenueRows = DB::table('orders')
            ->whereIn('status', self::ORDER_STATUSES)
            ->whereBetween('created_at', [$start.' 00:00:00', $end.' 23:59:59'])
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, SUM(grand_total) as revenue, SUM(grand_total_refunded) as refunds")
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        $expenseRows = DB::table('general_expenses')
            ->whereBetween('expense_date', [$start, $end])
            ->selectRaw("DATE_FORMAT(expense_date, '%Y-%m') as month, SUM(amount) as total")
            ->groupBy('month')
            ->pluck('total', 'month');

        $cogsRows = DB::table('order_items as oi')
            ->join('orders as o', 'o.id', '=', 'oi.order_id')
            ->join('product_costs as pc', 'pc.product_id', '=', 'oi.product_id')
            ->whereIn('o.status', self::ORDER_STATUSES)
            ->whereBetween('o.created_at', [$start.' 00:00:00', $end.' 23:59:59'])
            ->selectRaw("DATE_FORMAT(o.created_at, '%Y-%m') as month, SUM(oi.qty_ordered * (pc.cost_price + pc.manufacturing_fee + pc.shipping_cost_per_unit + pc.other_costs)) as total")
            ->groupBy('month')
            ->pluck('total', 'month');

        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $key  = $date->format('Y-m');
            $row  = $revenueRows[$key] ?? null;

            $rev     = (float) ($row?->revenue ?? 0);
            $refunds = (float) ($row?->refunds ?? 0);
            $exp     = (float) ($expenseRows[$key] ?? 0);
            $cogs    = (float) ($cogsRows[$key] ?? 0);
            $net     = $rev - $refunds;

            $months[] = [
                'label'   => $date->format('M Y'),
                'revenue' => round($rev, 2),
                'refunds' => round($refunds, 2),
                'profit'  => round($net - $cogs - $exp, 2),
                'costs'   => round($cogs + $exp, 2),
            ];
        }

        return $months;
    }

    private function recentTransactions(string $from, string $to): array
    {
        return FinancialTransaction::whereBetween('transaction_date', [$from, $to])
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->limit(50)
            ->get()
            ->map(fn ($tx) => [
                'id'               => $tx->id,
                'type'             => $tx->type,
                'description'      => $tx->description,
                'platform'         => $tx->platform,
                'amount'           => $tx->amount,
                'transaction_date' => $tx->transaction_date->format('d M Y'),
                'destroy_url'      => $tx->type === 'ad_spend'
                    ? route('admin.cost_management.ad_spend.destroy', $tx->id)
                    : null,
            ])
            ->toArray();
    }

    private function topProducts(string $from, string $to): array
    {
        return DB::table('order_items as oi')
            ->join('orders as o', 'o.id', '=', 'oi.order_id')
            ->join('products as p', 'p.id', '=', 'oi.product_id')
            ->leftJoin('product_costs as pc', 'pc.product_id', '=', 'oi.product_id')
            ->leftJoin('product_flat as pf', function ($join) {
                $join->on('pf.product_id', '=', 'oi.product_id')
                     ->where('pf.locale', app()->getLocale())
                     ->where('pf.channel', core()->getCurrentChannelCode());
            })
            ->whereIn('o.status', self::ORDER_STATUSES)
            ->whereBetween('o.created_at', [$from.' 00:00:00', $to.' 23:59:59'])
            ->groupBy('oi.product_id', 'pf.name')
            ->selectRaw('
                oi.product_id,
                COALESCE(pf.name, p.sku) as product_name,
                SUM(oi.qty_ordered) as units_sold,
                SUM(oi.total) as revenue,
                SUM(oi.qty_ordered * COALESCE(pc.cost_price + pc.manufacturing_fee + pc.shipping_cost_per_unit + pc.other_costs, 0)) as cogs,
                SUM(oi.total) - SUM(oi.qty_ordered * COALESCE(pc.cost_price + pc.manufacturing_fee + pc.shipping_cost_per_unit + pc.other_costs, 0)) as profit
            ')
            ->orderByDesc('profit')
            ->limit(10)
            ->get()
            ->toArray();
    }
}
