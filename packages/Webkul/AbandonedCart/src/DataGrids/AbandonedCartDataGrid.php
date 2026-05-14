<?php

namespace Webkul\AbandonedCart\DataGrids;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class AbandonedCartDataGrid extends DataGrid
{
    public function prepareQueryBuilder(): Builder
    {
        $queryBuilder = DB::table('cart')
            ->leftJoin('customers', 'cart.customer_id', '=', 'customers.id')
            ->leftJoin('channels', 'cart.channel_id', '=', 'channels.id')
            ->select(
                'cart.id',
                'cart.customer_email',
                'cart.customer_first_name',
                'cart.customer_last_name',
                DB::raw('CONCAT('.DB::getTablePrefix().'cart.customer_first_name, " ", '.DB::getTablePrefix().'cart.customer_last_name) as full_name'),
                'cart.base_grand_total',
                'cart.items_count',
                'cart.items_qty',
                'cart.notification_count',
                'cart.notification_opt_in',
                'cart.recovery_status',
                'cart.last_activity_at',
                'cart.created_at',
                'cart.is_guest',
                'channels.name as channel_name',
            )
            ->where('cart.is_active', 1)
            ->whereIn('cart.recovery_status', ['active', 'recovering', 'expired'])
            ->where(function ($q) {
                $q->whereNotNull('cart.customer_email')
                  ->orWhereNotNull('cart.customer_id');
            });

        $this->addFilter('full_name', DB::raw('CONCAT('.DB::getTablePrefix().'cart.customer_first_name, " ", '.DB::getTablePrefix().'cart.customer_last_name)'));
        $this->addFilter('last_activity_at', 'cart.last_activity_at');
        $this->addFilter('created_at', 'cart.created_at');

        return $queryBuilder;
    }

    public function prepareColumns(): void
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('abandoned-cart::app.admin.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'full_name',
            'label'      => trans('abandoned-cart::app.admin.datagrid.customer'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => false,
        ]);

        $this->addColumn([
            'index'      => 'customer_email',
            'label'      => trans('abandoned-cart::app.admin.datagrid.email'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => false,
        ]);

        $this->addColumn([
            'index'      => 'channel_name',
            'label'      => trans('abandoned-cart::app.admin.datagrid.channel'),
            'type'       => 'string',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => false,
        ]);

        $this->addColumn([
            'index'      => 'items_count',
            'label'      => trans('abandoned-cart::app.admin.datagrid.items'),
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'base_grand_total',
            'label'      => trans('abandoned-cart::app.admin.datagrid.total'),
            'type'       => 'decimal',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => fn ($row) => core()->formatBasePrice($row->base_grand_total),
        ]);

        $this->addColumn([
            'index'           => 'recovery_status',
            'label'           => trans('abandoned-cart::app.admin.datagrid.status'),
            'type'            => 'string',
            'searchable'      => false,
            'filterable'      => true,
            'filterable_type' => 'dropdown',
            'filterable_options' => collect(['active', 'recovering', 'expired'])->map(fn ($s) => [
                'label' => trans("abandoned-cart::app.admin.datagrid.status-{$s}"),
                'value' => $s,
            ])->toArray(),
            'sortable'        => true,
            'closure'         => function ($row) {
                $classes = [
                    'active'     => 'label-active',
                    'recovering' => 'label-processing',
                    'recovered'  => 'label-completed',
                    'expired'    => 'label-canceled',
                ];
                $class = $classes[$row->recovery_status] ?? '';

                return "<span class=\"badge {$class}\">".
                    trans("abandoned-cart::app.admin.datagrid.status-{$row->recovery_status}").
                    '</span>';
            },
        ]);

        $this->addColumn([
            'index'           => 'notification_count',
            'label'           => trans('abandoned-cart::app.admin.datagrid.notifications-sent'),
            'type'            => 'integer',
            'searchable'      => false,
            'filterable'      => true,
            'sortable'        => true,
        ]);

        $this->addColumn([
            'index'           => 'last_activity_at',
            'label'           => trans('abandoned-cart::app.admin.datagrid.last-activity'),
            'type'            => 'datetime',
            'searchable'      => false,
            'filterable'      => true,
            'filterable_type' => 'date_range',
            'sortable'        => true,
        ]);

        $this->addColumn([
            'index'           => 'created_at',
            'label'           => trans('abandoned-cart::app.admin.datagrid.created-at'),
            'type'            => 'datetime',
            'searchable'      => false,
            'filterable'      => true,
            'filterable_type' => 'date_range',
            'sortable'        => true,
        ]);
    }

    public function prepareActions(): void
    {
        $this->addAction([
            'icon'   => 'icon-send',
            'title'  => trans('abandoned-cart::app.admin.datagrid.send-now'),
            'method' => 'POST',
            'url'    => fn ($row) => route('admin.sales.abandoned-carts.send-now', $row->id),
        ]);

        $this->addAction([
            'icon'   => 'icon-done',
            'title'  => trans('abandoned-cart::app.admin.datagrid.mark-recovered'),
            'method' => 'PATCH',
            'url'    => fn ($row) => route('admin.sales.abandoned-carts.mark-recovered', $row->id),
        ]);

        $this->addAction([
            'icon'   => 'icon-cancel',
            'title'  => trans('abandoned-cart::app.admin.datagrid.expire'),
            'method' => 'PATCH',
            'url'    => fn ($row) => route('admin.sales.abandoned-carts.expire', $row->id),
        ]);
    }
}
