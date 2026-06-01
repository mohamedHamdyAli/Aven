<?php

namespace Webkul\CostManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\CostManagement\DataGrids\GeneralExpenseDataGrid;
use Webkul\CostManagement\Models\GeneralExpense;

class GeneralExpenseController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(GeneralExpenseDataGrid::class)->process();
        }

        $categories = GeneralExpense::categories();

        return view('cost_management::expenses.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'category'     => 'required|string',
            'amount'       => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'is_recurring' => 'boolean',
            'frequency'    => 'nullable|in:weekly,monthly,yearly',
            'notes'        => 'nullable|string|max:1000',
        ]);

        $data['is_recurring'] = $request->boolean('is_recurring');

        GeneralExpense::create($data);

        session()->flash('success', 'Expense added successfully.');

        return redirect()->route('admin.cost_management.expenses.index');
    }

    public function edit(int $id)
    {
        $expense    = GeneralExpense::findOrFail($id);
        $categories = GeneralExpense::categories();

        return view('cost_management::expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, int $id)
    {
        $expense = GeneralExpense::findOrFail($id);

        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'category'     => 'required|string',
            'amount'       => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'is_recurring' => 'boolean',
            'frequency'    => 'nullable|in:weekly,monthly,yearly',
            'notes'        => 'nullable|string|max:1000',
        ]);

        $data['is_recurring'] = $request->boolean('is_recurring');

        $expense->update($data);

        session()->flash('success', 'Expense updated successfully.');

        return redirect()->route('admin.cost_management.expenses.index');
    }

    public function destroy(int $id)
    {
        GeneralExpense::findOrFail($id)->delete();

        return response()->json(['message' => 'Expense deleted.']);
    }
}
