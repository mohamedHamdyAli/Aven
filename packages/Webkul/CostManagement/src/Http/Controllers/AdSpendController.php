<?php

namespace Webkul\CostManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\CostManagement\Models\FinancialTransaction;

class AdSpendController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'platform'         => 'required|string|max:100',
            'description'      => 'required|string|max:255',
            'amount'           => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
        ]);

        FinancialTransaction::create([
            'type'             => 'ad_spend',
            'amount'           => -(float) $data['amount'],
            'description'      => $data['description'],
            'platform'         => $data['platform'],
            'reference_type'   => 'ad_spend',
            'transaction_date' => $data['transaction_date'],
        ]);

        session()->flash('success', 'Ad spend recorded successfully.');

        return redirect()->back();
    }

    public function destroy(int $id)
    {
        $tx = FinancialTransaction::where('type', 'ad_spend')->findOrFail($id);
        $tx->delete();

        return response()->json(['message' => 'Deleted.']);
    }
}
