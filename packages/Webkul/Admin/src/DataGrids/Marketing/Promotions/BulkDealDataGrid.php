<?php

namespace Webkul\Admin\DataGrids\Marketing\Promotions;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class BulkDealDataGrid extends DataGrid
{
    public function prepareQueryBuilder(): Builder
    {
        return DB::table('bulk_deals')->select(
            'id',
            'name',
            'paid_quantity',
            'deal_quantity',
            'deal_price',
            'status',
            'starts_from',
            'ends_till',
            'sort_order'
        );
    }

    public function prepareColumns(): void
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.marketing.promotions.bulk-deals.index.datagrid.id'),
            'type'       => 'integer',
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('admin::app.marketing.promotions.bulk-deals.index.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'paid_quantity',
            'label'      => trans('admin::app.marketing.promotions.bulk-deals.index.datagrid.paid-qty'),
            'type'       => 'integer',
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'deal_quantity',
            'label'      => trans('admin::app.marketing.promotions.bulk-deals.index.datagrid.deal-qty'),
            'type'       => 'integer',
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'deal_price',
            'label'      => trans('admin::app.marketing.promotions.bulk-deals.index.datagrid.deal-price'),
            'type'       => 'decimal',
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'   => 'status',
            'label'   => trans('admin::app.marketing.promotions.bulk-deals.index.datagrid.status'),
            'type'    => 'boolean',
            'sortable' => true,
            'closure' => function ($value) {
                return $value->status
                    ? '<span class="label-active">'.trans('admin::app.marketing.promotions.bulk-deals.index.datagrid.active').'</span>'
                    : '<span class="label-info">'.trans('admin::app.marketing.promotions.bulk-deals.index.datagrid.inactive').'</span>';
            },
        ]);

        $this->addColumn([
            'index'      => 'starts_from',
            'label'      => trans('admin::app.marketing.promotions.bulk-deals.index.datagrid.start'),
            'type'       => 'datetime',
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'ends_till',
            'label'      => trans('admin::app.marketing.promotions.bulk-deals.index.datagrid.end'),
            'type'       => 'datetime',
            'filterable' => true,
            'sortable'   => true,
        ]);
    }

    public function prepareActions(): void
    {
        $this->addAction([
            'icon'   => 'icon-edit',
            'title'  => trans('admin::app.marketing.promotions.bulk-deals.index.datagrid.edit'),
            'method' => 'GET',
            'url'    => function ($row) {
                return route('admin.marketing.promotions.bulk_deals.edit', $row->id);
            },
        ]);

        $this->addAction([
            'icon'   => 'icon-delete',
            'title'  => trans('admin::app.marketing.promotions.bulk-deals.index.datagrid.delete'),
            'method' => 'DELETE',
            'url'    => function ($row) {
                return route('admin.marketing.promotions.bulk_deals.delete', $row->id);
            },
        ]);
    }
}
