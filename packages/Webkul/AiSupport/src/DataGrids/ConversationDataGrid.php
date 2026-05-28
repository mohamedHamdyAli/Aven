<?php

namespace Webkul\AiSupport\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class ConversationDataGrid extends DataGrid
{
    public function prepareQueryBuilder()
    {
        return DB::table('ai_support_conversations as c')
            ->leftJoin('customers as cu', 'cu.id', '=', 'c.customer_id')
            ->leftJoin('ai_support_messages as m', function ($join) {
                $join->on('m.conversation_id', '=', 'c.id')
                    ->whereRaw('m.id = (SELECT MAX(id) FROM ai_support_messages WHERE conversation_id = c.id)');
            })
            ->select(
                'c.id',
                'c.channel',
                'c.channel_identifier',
                'c.status',
                'c.created_at',
                DB::raw("CONCAT(cu.first_name, ' ', cu.last_name) as customer_name"),
                'cu.email as customer_email',
                DB::raw('LEFT(m.content, 80) as last_message'),
                'm.created_at as last_message_at'
            );
    }

    public function prepareColumns(): void
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => 'ID',
            'type'        => 'integer',
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'channel',
            'label'      => 'Channel',
            'type'        => 'string',
            'sortable'   => true,
            'filterable' => true,
            'closure'    => fn ($row) => ucfirst(str_replace('_', ' ', $row->channel)),
        ]);

        $this->addColumn([
            'index'      => 'customer_name',
            'label'      => 'Customer',
            'type'        => 'string',
            'sortable'   => true,
            'filterable' => true,
            'closure'    => fn ($row) => $row->customer_name ?: $row->channel_identifier,
        ]);

        $this->addColumn([
            'index'      => 'last_message',
            'label'      => 'Last Message',
            'type'        => 'string',
            'sortable'   => false,
            'filterable' => false,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => 'Status',
            'type'        => 'string',
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($row) {
                $colors = [
                    'open'           => 'bg-green-100 text-green-800',
                    'pending_review' => 'bg-yellow-100 text-yellow-800',
                    'human_handoff'  => 'bg-blue-100 text-blue-800',
                    'closed'         => 'bg-gray-100 text-gray-600',
                ];
                $color = $colors[$row->status] ?? 'bg-gray-100 text-gray-600';
                $label = ucfirst(str_replace('_', ' ', $row->status));

                return "<span class=\"px-2 py-1 rounded text-xs font-medium {$color}\">{$label}</span>";
            },
        ]);

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => 'Started',
            'type'        => 'datetime',
            'sortable'   => true,
            'filterable' => true,
        ]);
    }

    public function prepareActions(): void
    {
        $this->addAction([
            'icon'   => 'icon-eye',
            'title'  => 'View',
            'method' => 'GET',
            'url'    => fn ($row) => route('admin.ai-support.conversations.show', $row->id),
        ]);
    }
}
