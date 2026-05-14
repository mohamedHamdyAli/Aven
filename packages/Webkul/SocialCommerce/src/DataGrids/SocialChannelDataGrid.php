<?php

namespace Webkul\SocialCommerce\DataGrids;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class SocialChannelDataGrid extends DataGrid
{
    public function prepareQueryBuilder(): Builder
    {
        return DB::table('social_channel_platforms as scp')
            ->join('channels as c', 'scp.channel_id', '=', 'c.id')
            ->select(
                'scp.id',
                'c.code as channel_code',
                'scp.platform',
                'scp.is_active',
                'scp.page_url',
                'scp.last_synced_at',
                'scp.created_at',
            );
    }

    public function prepareColumns(): void
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('social-commerce::app.admin.social-channels.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'channel_code',
            'label'      => trans('social-commerce::app.admin.social-channels.index.datagrid.channel'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'platform',
            'label'      => trans('social-commerce::app.admin.social-channels.index.datagrid.platform'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'is_active',
            'label'      => trans('social-commerce::app.admin.social-channels.index.datagrid.status'),
            'type'       => 'boolean',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'last_synced_at',
            'label'      => trans('social-commerce::app.admin.social-channels.index.datagrid.last-synced'),
            'type'       => 'datetime',
            'searchable' => false,
            'filterable' => false,
            'sortable'   => true,
        ]);
    }

    public function prepareActions(): void
    {
        $this->addAction([
            'icon'   => 'icon-repeat',
            'title'  => trans('social-commerce::app.admin.social-channels.index.datagrid.sync'),
            'method' => 'GET',
            'url'    => function ($row) {
                return route('admin.social-commerce.channels.sync', $row->id);
            },
        ]);

        $this->addAction([
            'icon'   => 'icon-edit',
            'title'  => trans('admin::app.components.datagrid.toolbar.edit'),
            'method' => 'GET',
            'url'    => function ($row) {
                return route('admin.social-commerce.channels.edit', $row->id);
            },
        ]);

        $this->addAction([
            'icon'   => 'icon-delete',
            'title'  => trans('admin::app.components.datagrid.toolbar.delete'),
            'method' => 'DELETE',
            'url'    => function ($row) {
                return route('admin.social-commerce.channels.destroy', $row->id);
            },
        ]);
    }
}
