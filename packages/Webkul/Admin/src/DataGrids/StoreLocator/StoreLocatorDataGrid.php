<?php

namespace Webkul\Admin\DataGrids\StoreLocator;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class StoreLocatorDataGrid extends DataGrid
{
    public function prepareQueryBuilder(): Builder
    {
        return DB::table('store_locators')
            ->select('id', 'name', 'address', 'phone', 'status');
    }

    public function prepareColumns(): void
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.store-locator.index.datagrid.id'),
            'type'       => 'integer',
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('admin::app.store-locator.index.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'address',
            'label'      => trans('admin::app.store-locator.index.datagrid.address'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'phone',
            'label'      => trans('admin::app.store-locator.index.datagrid.phone'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'            => 'status',
            'label'            => trans('admin::app.store-locator.index.datagrid.status'),
            'type'             => 'boolean',
            'filterable'       => true,
            'filterable_type'  => 'dropdown',
            'filterable_options' => [
                ['label' => trans('admin::app.store-locator.index.datagrid.active'), 'value' => 1],
                ['label' => trans('admin::app.store-locator.index.datagrid.inactive'), 'value' => 0],
            ],
            'sortable'   => true,
            'closure'    => fn ($row) => $row->status
                ? '<span class="label-active">'.trans('admin::app.store-locator.index.datagrid.active').'</span>'
                : '<span class="label-info">'.trans('admin::app.store-locator.index.datagrid.inactive').'</span>',
        ]);
    }

    public function prepareActions(): void
    {
        $this->addAction([
            'icon'   => 'icon-edit',
            'title'  => trans('admin::app.store-locator.index.datagrid.edit'),
            'method' => 'GET',
            'url'    => fn ($row) => route('admin.store-locator.edit', $row->id),
        ]);

        $this->addAction([
            'icon'   => 'icon-delete',
            'title'  => trans('admin::app.store-locator.index.datagrid.delete'),
            'method' => 'DELETE',
            'url'    => fn ($row) => route('admin.store-locator.destroy', $row->id),
        ]);
    }

    public function prepareMassActions(): void
    {
        $this->addMassAction([
            'title'  => trans('admin::app.store-locator.index.datagrid.delete'),
            'method' => 'POST',
            'url'    => route('admin.store-locator.mass_delete'),
        ]);
    }
}
