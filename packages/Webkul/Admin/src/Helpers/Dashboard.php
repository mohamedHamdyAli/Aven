<?php

namespace Webkul\Admin\Helpers;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Webkul\Admin\Helpers\Reporting\Customer;
use Webkul\Admin\Helpers\Reporting\Product;
use Webkul\Admin\Helpers\Reporting\Sale;
use Webkul\Admin\Repositories\ChannelAdSpendRepository;

class Dashboard
{
    /**
     * Create a controller instance.
     *
     * @return void
     */
    public function __construct(
        protected Sale $saleReporting,
        protected Product $productReporting,
        protected Customer $customerReporting,
        protected ChannelAdSpendRepository $channelAdSpendRepository
    ) {}

    /**
     * Returns the overall statistics.
     */
    public function getOverAllStats(): array
    {
        return [
            'total_customers' => $this->customerReporting->getTotalCustomersProgress(),
            'total_orders' => $this->saleReporting->getTotalOrdersProgress(),
            'total_sales' => $this->saleReporting->getTotalSalesProgress(),
            'avg_sales' => $this->saleReporting->getAverageSalesProgress(),
            'total_unpaid_invoices' => [
                'total' => $total = $this->saleReporting->getTotalPendingInvoicesAmount(),
                'formatted_total' => core()->formatBasePrice($total),
            ],
        ];
    }

    /**
     * Returns the today statistics.
     */
    public function getTodayStats(): array
    {
        $orders = $this->saleReporting->getTodayOrders();

        $orders = $orders->map(function ($order) {
            return [
                'id' => $order->id,
                'increment_id' => $order->id,
                'status' => $order->status,
                'status_label' => $order->status_label,
                'payment_method' => core()->getConfigData('sales.payment_methods.'.$order->payment->method.'.title'),
                'base_grand_total' => $order->base_grand_total,
                'formatted_base_grand_total' => core()->formatBasePrice($order->base_grand_total),
                'channel_name' => $order->channel_name,
                'customer_email' => $order->customer_email,
                'customer_name' => $order->customer_full_name,
                'items' => view('admin::sales.orders.items', compact('order'))->render(),
                'billing_address' => $order?->billing_address->city.($order?->billing_address->country ? ', '.core()->country_name($order?->billing_address->country) : ''),
                'created_at' => $order->created_at->format('d M Y, H:i:s'),
            ];
        });

        return [
            'total_sales' => $this->saleReporting->getTodaySalesProgress(),
            'total_orders' => $this->saleReporting->getTodayOrdersProgress(),
            'total_customers' => $this->customerReporting->getTodayCustomersProgress(),
            'orders' => $orders,
        ];
    }

    /**
     * Returns the today statistics.
     *
     * @return EloquentCollection
     */
    public function getStockThresholdProducts()
    {
        $products = $this->productReporting->getStockThresholdProducts(5);

        $products = $products->map(function ($product) {
            return [
                'id' => $product->product_id,
                'sku' => $product->product->sku,
                'name' => $product->product->name,
                'price' => $product->product->price,
                'formatted_price' => core()->formatBasePrice($product->product->price),
                'total_qty' => $product->total_qty,
                'image' => $product->product->base_image_url,
            ];
        });

        return $products;
    }

    /**
     * Returns sales statistics.
     */
    public function getSalesStats(): array
    {
        return [
            'total_orders' => $this->saleReporting->getTotalOrdersProgress(),
            'total_sales' => $this->saleReporting->getTotalSalesProgress(),
            'over_time' => $this->saleReporting->getCurrentTotalSalesOverTime(),
        ];
    }

    /**
     * Returns top selling products statistics.
     */
    public function getTopSellingProducts(): Collection
    {
        return $this->productReporting->getTopSellingProductsByRevenue(5);
    }

    /**
     * Returns top customers statistics.
     */
    public function getTopCustomers(): EloquentCollection
    {
        $customers = $this->customerReporting->getCustomersWithMostSales(5);

        $customers->map(function ($customer) {
            $customer->formatted_total = core()->formatBasePrice($customer->total);
        });

        return $customers;
    }

    /**
     * Get the start date.
     *
     * @return \Carbon\Carbon
     */
    public function getStartDate(): Carbon
    {
        return $this->saleReporting->getStartDate();
    }

    /**
     * Get the end date.
     *
     * @return \Carbon\Carbon
     */
    public function getEndDate(): Carbon
    {
        return $this->saleReporting->getEndDate();
    }

    /**
     * Returns per-channel stats: orders, revenue, ad spend, ROAS.
     */
    public function getChannelsStats(): array
    {
        $startDate = $this->saleReporting->getStartDate();
        $endDate   = $this->saleReporting->getEndDate();

        $orderStats = DB::table('orders')
            ->select(
                'channel_id',
                DB::raw('COUNT(*) as orders_count'),
                DB::raw('SUM(base_grand_total_invoiced - base_grand_total_refunded) as revenue')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('channel_id')
            ->groupBy('channel_id')
            ->get()
            ->keyBy('channel_id');

        return core()->getAllChannels()->map(function ($channel) use ($orderStats, $startDate, $endDate) {
            $stats       = $orderStats->get($channel->id);
            $ordersCount = $stats ? (int) $stats->orders_count : 0;
            $revenue     = $stats ? (float) $stats->revenue : 0.0;
            $adSpend     = $this->channelAdSpendRepository->getSpendForPeriod($channel->id, $startDate, $endDate);
            $roas        = $adSpend > 0 ? round($revenue / $adSpend, 2) : null;
            $costPerOrder = ($adSpend > 0 && $ordersCount > 0) ? round($adSpend / $ordersCount, 2) : null;

            return [
                'id'                   => $channel->id,
                'name'                 => $channel->name,
                'code'                 => $channel->code,
                'orders_count'         => $ordersCount,
                'revenue'              => $revenue,
                'formatted_revenue'    => core()->formatBasePrice($revenue),
                'ad_spend'             => $adSpend,
                'formatted_ad_spend'   => core()->formatBasePrice($adSpend),
                'roas'                 => $roas,
                'cost_per_order'       => $costPerOrder,
                'formatted_cost_per_order' => $costPerOrder ? core()->formatBasePrice($costPerOrder) : null,
            ];
        })->values()->toArray();
    }

    /**
     * Returns date range
     */
    public function getDateRange(): string
    {
        return $this->getStartDate()->translatedFormat('d M').' - '.$this->getEndDate()->translatedFormat('d M');
    }
}
