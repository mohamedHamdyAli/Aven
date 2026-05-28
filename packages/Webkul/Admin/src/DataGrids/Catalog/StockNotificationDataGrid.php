<?php

namespace Webkul\Admin\DataGrids\Catalog;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class StockNotificationDataGrid extends DataGrid
{
    public function prepareQueryBuilder(): Builder
    {
        return DB::table('stock_notifications as sn')
            ->join('products as p', 'p.id', '=', 'sn.product_id')
            ->leftJoin('product_flat as pf', function ($join) {
                $join->on('pf.product_id', '=', 'sn.product_id')
                    ->where('pf.locale', app()->getLocale())
                    ->where('pf.channel', core()->getCurrentChannelCode());
            })
            ->select(
                'sn.id',
                'sn.product_id',
                'pf.name as product_name',
                'sn.email',
                'sn.phone',
                'sn.notified',
                'sn.created_at',
            );
    }

    public function prepareColumns(): void
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => 'ID',
            'type'       => 'integer',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'product_name',
            'label'      => 'Product',
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'email',
            'label'      => 'Email',
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'phone',
            'label'      => 'Phone',
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => false,
            'filterable' => false,
        ]);

        $this->addColumn([
            'index'      => 'notified',
            'label'      => 'Notified',
            'type'       => 'boolean',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => fn ($row) => $row->notified ? 'Yes' : 'Pending',
        ]);

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => 'Subscribed At',
            'type'       => 'datetime',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
    }

    public function prepareActions(): void
    {
        $this->addAction([
            'icon'   => 'icon-delete',
            'title'  => 'Delete',
            'method' => 'DELETE',
            'url'    => fn ($row) => route('admin.catalog.stock_notifications.destroy', $row->id),
        ]);
    }

    public function prepareMassActions(): void
    {
        $this->addMassAction([
            'title'  => 'Delete',
            'method' => 'POST',
            'url'    => route('admin.catalog.stock_notifications.mass_destroy'),
        ]);
    }
}
