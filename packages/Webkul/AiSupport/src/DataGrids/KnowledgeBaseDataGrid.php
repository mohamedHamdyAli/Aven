<?php

namespace Webkul\AiSupport\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class KnowledgeBaseDataGrid extends DataGrid
{
    public function prepareQueryBuilder()
    {
        return DB::table('ai_support_knowledge_base')
            ->select('id', 'question', 'is_active', 'sort_order', 'created_at')
            ->orderBy('sort_order');
    }

    public function prepareColumns(): void
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => 'ID',
            'type'        => 'integer',
            'sortable'   => true,
            'filterable' => false,
        ]);

        $this->addColumn([
            'index'      => 'question',
            'label'      => 'Question',
            'type'        => 'string',
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'is_active',
            'label'      => 'Active',
            'type'        => 'boolean',
            'sortable'   => true,
            'filterable' => true,
            'closure'    => fn ($row) => $row->is_active
                ? '<span class="px-2 py-1 rounded text-xs bg-green-100 text-green-800">Yes</span>'
                : '<span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-500">No</span>',
        ]);

        $this->addColumn([
            'index'      => 'sort_order',
            'label'      => 'Sort Order',
            'type'        => 'integer',
            'sortable'   => true,
            'filterable' => false,
        ]);
    }

    public function prepareActions(): void
    {
        $this->addAction([
            'icon'   => 'icon-delete',
            'title'  => 'Delete',
            'method' => 'DELETE',
            'url'    => fn ($row) => route('admin.ai-support.knowledge-base.destroy', $row->id),
        ]);
    }
}
