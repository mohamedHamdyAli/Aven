<?php

namespace Webkul\CostManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\CostManagement\Models\Shareholder;

class ShareholderController extends Controller
{
    public function index()
    {
        $shareholders   = Shareholder::orderBy('percentage', 'desc')->get();
        $totalPercentage = $shareholders->where('active', true)->sum('percentage');

        return view('cost_management::shareholders.index', compact('shareholders', 'totalPercentage'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:191',
            'email'      => 'nullable|email|max:191',
            'phone'      => 'nullable|string|max:30',
            'percentage' => 'required|numeric|min:0.01|max:100',
            'joined_at'  => 'nullable|date',
            'notes'      => 'nullable|string',
        ]);

        // Warn if total would exceed 100%
        $existingTotal = Shareholder::where('active', true)->sum('percentage');
        if ($existingTotal + $data['percentage'] > 100) {
            return back()->withErrors(['percentage' => 'Total shares would exceed 100%. Current allocated: ' . $existingTotal . '%'])->withInput();
        }

        Shareholder::create($data + ['active' => true]);

        return back()->with('success', 'Shareholder added successfully.');
    }

    public function update(Request $request, Shareholder $shareholder)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:191',
            'email'      => 'nullable|email|max:191',
            'phone'      => 'nullable|string|max:30',
            'percentage' => 'required|numeric|min:0.01|max:100',
            'joined_at'  => 'nullable|date',
            'active'     => 'boolean',
            'notes'      => 'nullable|string',
        ]);

        $shareholder->update($data);

        return back()->with('success', 'Shareholder updated.');
    }

    public function destroy(Shareholder $shareholder)
    {
        $shareholder->delete();

        return back()->with('success', 'Shareholder removed.');
    }
}
