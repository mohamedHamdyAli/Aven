<?php

namespace Webkul\CostManagement\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class GeneralExpenseDataGrid extends DataGrid
{
    public function prepareQueryBuilder()
    {
        return DB::table('general_expenses')
            ->select('id', 'title', 'category', 'amount', 'expense_date', 'is_recurring', 'frequency');
    }

    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'title',
            'label'      => 'Title',
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'    => 'category',
            'label'    => 'Category',
            'type'     => 'string',
            'sortable' => true,
        ]);

        $this->addColumn([
            'index'    => 'amount',
            'label'    => 'Amount',
            'type'     => 'string',
            'sortable' => true,
            'closure'  => fn ($row) => core()->formatPrice($row->amount),
        ]);

        $this->addColumn([
            'index'    => 'expense_date',
            'label'    => 'Date',
            'type'     => 'string',
            'sortable' => true,
        ]);

        $this->addColumn([
            'index'   => 'is_recurring',
            'label'   => 'Recurring',
            'type'    => 'string',
            'closure' => fn ($row) => $row->is_recurring
                ? "<span class=\"badge badge-md badge-success\">{$row->frequency}</span>"
                : '<span class="badge badge-md badge-secondary">One-time</span>',
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'icon'   => 'icon-edit',
            'title'  => 'Edit',
            'method' => 'GET',
            'url'    => fn ($row) => route('admin.cost_management.expenses.edit', $row->id),
        ]);

        $this->addAction([
            'icon'   => 'icon-delete',
            'title'  => 'Delete',
            'method' => 'DELETE',
            'url'    => fn ($row) => route('admin.cost_management.expenses.destroy', $row->id),
        ]);
    }
}
