<?php

namespace Webkul\CostManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\CostManagement\Models\CapitalContribution;

class CapitalContributionController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'shareholder_id' => 'required|integer|exists:shareholders,id',
            'amount'         => 'required|numeric|min:0.01',
            'contributed_at' => 'required|date',
            'type'           => 'required|in:cash,asset,loan_repayment,other',
            'notes'          => 'nullable|string|max:500',
        ]);

        CapitalContribution::create($data);

        return back()->with('success', 'Capital contribution recorded.');
    }

    public function destroy(CapitalContribution $contribution)
    {
        $contribution->delete();

        return back()->with('success', 'Contribution removed.');
    }
}
