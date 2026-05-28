<?php

namespace Webkul\CostManagement\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class ProductCostDataGrid extends DataGrid
{
    public function prepareQueryBuilder()
    {
        $locale  = app()->getLocale();
        $channel = core()->getCurrentChannelCode();

        return DB::table('products as p')
            ->leftJoin('product_flat as pf', function ($join) use ($locale, $channel) {
                $join->on('pf.product_id', '=', 'p.id')
                     ->where('pf.locale', $locale)
                     ->where('pf.channel', $channel);
            })
            ->leftJoin('product_costs as pc', 'pc.product_id', '=', 'p.id')
            ->where('pf.status', 1)
            ->selectRaw('
                p.id,
                p.sku,
                COALESCE(pf.name, p.sku) as product_name,
                COALESCE(pf.price, 0) as selling_price,
                COALESCE(pc.cost_price, 0) as cost_price,
                COALESCE(pc.manufacturing_fee, 0) as manufacturing_fee,
                COALESCE(pc.shipping_cost_per_unit, 0) as shipping_cost_per_unit,
                COALESCE(pc.other_costs, 0) as other_costs,
                COALESCE(pc.cost_price, 0) + COALESCE(pc.manufacturing_fee, 0) + COALESCE(pc.shipping_cost_per_unit, 0) + COALESCE(pc.other_costs, 0) as total_cost
            ');
    }

    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'product_name',
            'label'      => 'Product',
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'    => 'sku',
            'label'    => 'SKU',
            'type'     => 'string',
            'sortable' => true,
        ]);

        $this->addColumn([
            'index'    => 'selling_price',
            'label'    => 'Selling Price',
            'type'     => 'string',
            'sortable' => true,
            'closure'  => fn ($row) => core()->formatPrice($row->selling_price),
        ]);

        $this->addColumn([
            'index'    => 'total_cost',
            'label'    => 'Total Cost',
            'type'     => 'string',
            'sortable' => true,
            'closure'  => fn ($row) => core()->formatPrice($row->total_cost),
        ]);

        $this->addColumn([
            'index'   => 'margin',
            'label'   => 'Margin %',
            'type'    => 'string',
            'closure' => function ($row) {
                if ($row->selling_price <= 0) {
                    return '<span class="text-gray-400">—</span>';
                }
                $profit = $row->selling_price - $row->total_cost;
                $margin = round(($profit / $row->selling_price) * 100, 1);
                $color  = $margin >= 30 ? 'text-green-600' : ($margin >= 10 ? 'text-yellow-600' : 'text-red-600');

                return "<span class=\"font-semibold {$color}\">{$margin}%</span>";
            },
        ]);

        $this->addColumn([
            'index'   => 'profit_per_unit',
            'label'   => 'Profit / Unit',
            'type'    => 'string',
            'closure' => fn ($row) => core()->formatPrice($row->selling_price - $row->total_cost),
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'icon'   => 'icon-edit',
            'title'  => 'Edit Costs',
            'method' => 'GET',
            'url'    => fn ($row) => route('admin.cost_management.products.edit', $row->id),
        ]);
    }
}
