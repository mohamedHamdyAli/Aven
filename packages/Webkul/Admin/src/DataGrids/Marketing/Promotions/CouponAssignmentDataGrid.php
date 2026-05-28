<?php

namespace Webkul\Admin\DataGrids\Marketing\Promotions;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class CouponAssignmentDataGrid extends DataGrid
{
    public function prepareQueryBuilder(): Builder
    {
        return DB::table('coupon_assignment_campaigns as cac')
            ->join('cart_rules as cr', 'cr.id', '=', 'cac.cart_rule_id')
            ->leftJoin('cart_rule_coupon_assignments as cca', 'cca.campaign_id', '=', 'cac.id')
            ->leftJoin('cart_rule_coupons as crc', 'crc.id', '=', 'cca.cart_rule_coupon_id')
            ->select(
                'cac.id',
                'cac.name as campaign_name',
                'cr.name as cart_rule_name',
                'cr.discount_amount',
                'cr.action_type',
                DB::raw('COUNT(cca.id) as total'),
                DB::raw('SUM(IF(crc.times_used > 0, 1, 0)) as used_count'),
                'cac.created_at',
            )
            ->groupBy('cac.id', 'cac.name', 'cr.name', 'cr.discount_amount', 'cr.action_type', 'cac.created_at');
    }

    public function prepareColumns(): void
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => '#',
            'type'       => 'integer',
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'campaign_name',
            'label'      => trans('admin::app.marketing.promotions.coupon-assignments.datagrid.campaign'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'cart_rule_name',
            'label'      => trans('admin::app.marketing.promotions.coupon-assignments.datagrid.cart-rule'),
            'type'       => 'string',
            'searchable' => true,
        ]);

        $this->addColumn([
            'index'   => 'discount_amount',
            'label'   => trans('admin::app.marketing.promotions.coupon-assignments.datagrid.discount'),
            'type'    => 'string',
            'closure' => fn ($row) => match ($row->action_type) {
                'by_percent' => number_format($row->discount_amount, 0) . '%',
                'by_fixed'   => number_format($row->discount_amount, 2) . ' EGP',
                default      => $row->discount_amount,
            },
        ]);

        $this->addColumn([
            'index'    => 'total',
            'label'    => trans('admin::app.marketing.promotions.coupon-assignments.datagrid.total'),
            'type'     => 'integer',
            'sortable' => true,
        ]);

        $this->addColumn([
            'index'    => 'used_count',
            'label'    => trans('admin::app.marketing.promotions.coupon-assignments.datagrid.used-count'),
            'type'     => 'integer',
            'sortable' => true,
            'closure'  => fn ($row) => $row->used_count . ' / ' . $row->total,
        ]);

        $this->addColumn([
            'index'    => 'created_at',
            'label'    => trans('admin::app.marketing.promotions.coupon-assignments.datagrid.created-at'),
            'type'     => 'datetime',
            'sortable' => true,
        ]);
    }

    public function prepareActions(): void
    {
        $this->addAction([
            'icon'   => 'icon-view',
            'title'  => trans('admin::app.marketing.promotions.coupon-assignments.datagrid.view'),
            'method' => 'GET',
            'url'    => fn ($row) => route('admin.marketing.promotions.coupon_assignments.show', $row->id),
        ]);

        $this->addAction([
            'icon'   => 'icon-delete',
            'title'  => trans('admin::app.marketing.promotions.coupon-assignments.datagrid.delete'),
            'method' => 'DELETE',
            'url'    => fn ($row) => route('admin.marketing.promotions.coupon_assignments.destroy', $row->id),
        ]);
    }

    public function prepareMassActions(): void
    {
        $this->addMassAction([
            'title'  => trans('admin::app.marketing.promotions.coupon-assignments.datagrid.mass-delete'),
            'method' => 'POST',
            'url'    => route('admin.marketing.promotions.coupon_assignments.mass_destroy'),
        ]);
    }
}
