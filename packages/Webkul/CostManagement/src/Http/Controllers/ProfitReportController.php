<?php

namespace Webkul\CostManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Webkul\CostManagement\Models\FinancialTransaction;
use Webkul\CostManagement\Models\GeneralExpense;

class ProfitReportController extends Controller
{
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
            ];
        });

        return response()->json($payload);
    }

    private function calcStats(string $from, string $to): array
    {
        $revenue = DB::table('orders')
            ->whereIn('status', ['completed', 'processing', 'closed'])
            ->whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])
            ->sum('grand_total');

        $cogs = DB::table('order_items as oi')
            ->join('orders as o', 'o.id', '=', 'oi.order_id')
            ->join('product_costs as pc', 'pc.product_id', '=', 'oi.product_id')
            ->whereIn('o.status', ['completed', 'processing', 'closed'])
            ->whereBetween('o.created_at', [$from.' 00:00:00', $to.' 23:59:59'])
            ->selectRaw('SUM(oi.qty_ordered * (pc.cost_price + pc.manufacturing_fee + pc.shipping_cost_per_unit + pc.other_costs)) as total')
            ->value('total') ?? 0;

        $expenses = GeneralExpense::whereBetween('expense_date', [$from, $to])->sum('amount');

        $adSpend = abs(FinancialTransaction::where('type', 'ad_spend')
            ->whereBetween('transaction_date', [$from, $to])
            ->sum('amount'));

        $refunds = abs(FinancialTransaction::where('type', 'refund')
            ->whereBetween('transaction_date', [$from, $to])
            ->sum('amount'));

        $grossProfit = $revenue - $cogs - $refunds;
        $netProfit   = $grossProfit - $expenses - $adSpend;

        return [
            'revenue'      => round($revenue, 2),
            'cogs'         => round($cogs, 2),
            'gross_profit' => round($grossProfit, 2),
            'expenses'     => round($expenses, 2),
            'net_profit'   => round($netProfit, 2),
            'refunds'      => round($refunds, 2),
            'ad_spend'     => round($adSpend, 2),
            'margin'       => $revenue > 0 ? round(($netProfit / $revenue) * 100, 1) : 0,
            'orders_count' => DB::table('orders')
                ->whereIn('status', ['completed', 'processing', 'closed'])
                ->whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])
                ->count(),
        ];
    }

    private function monthlyChart(): array
    {
        // 3 queries instead of 36 (was: 3 queries × 12 months loop)
        $start = now()->subMonths(11)->startOfMonth()->toDateString();
        $end   = now()->endOfMonth()->toDateString();

        $revenueRows = DB::table('orders')
            ->whereIn('status', ['completed', 'processing', 'closed'])
            ->whereBetween('created_at', [$start.' 00:00:00', $end.' 23:59:59'])
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, SUM(grand_total) as total")
            ->groupBy('month')
            ->pluck('total', 'month');

        $expenseRows = DB::table('general_expenses')
            ->whereBetween('expense_date', [$start, $end])
            ->selectRaw("DATE_FORMAT(expense_date, '%Y-%m') as month, SUM(amount) as total")
            ->groupBy('month')
            ->pluck('total', 'month');

        $cogsRows = DB::table('order_items as oi')
            ->join('orders as o', 'o.id', '=', 'oi.order_id')
            ->join('product_costs as pc', 'pc.product_id', '=', 'oi.product_id')
            ->whereIn('o.status', ['completed', 'processing', 'closed'])
            ->whereBetween('o.created_at', [$start.' 00:00:00', $end.' 23:59:59'])
            ->selectRaw("DATE_FORMAT(o.created_at, '%Y-%m') as month, SUM(oi.qty_ordered * (pc.cost_price + pc.manufacturing_fee + pc.shipping_cost_per_unit + pc.other_costs)) as total")
            ->groupBy('month')
            ->pluck('total', 'month');

        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $key  = $date->format('Y-m');

            $rev  = (float) ($revenueRows[$key] ?? 0);
            $exp  = (float) ($expenseRows[$key] ?? 0);
            $cogs = (float) ($cogsRows[$key] ?? 0);

            $months[] = [
                'label'   => $date->format('M Y'),
                'revenue' => round($rev, 2),
                'profit'  => round($rev - $cogs - $exp, 2),
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
            ->whereIn('o.status', ['completed', 'processing', 'closed'])
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
