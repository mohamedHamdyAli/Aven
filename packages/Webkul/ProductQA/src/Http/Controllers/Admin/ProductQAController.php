<?php

namespace Webkul\ProductQA\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\ProductQA\Models\ProductQuestion;

class ProductQAController extends Controller
{
    public function index()
    {
        $questions = ProductQuestion::with([])
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
            ->orderByDesc('created_at')
            ->paginate(25);

        return view('product_qa::admin.index', compact('questions'));
    }

    public function answer(Request $request, int $id)
    {
        $data = $request->validate([
            'answer'       => 'required|string|max:2000',
            'is_published' => 'boolean',
        ]);

        $q = ProductQuestion::findOrFail($id);
        $q->update([
            'answer'       => $data['answer'],
            'status'       => 'approved',
            'is_published' => $data['is_published'] ?? true,
        ]);

        session()->flash('success', 'Answer saved and published.');

        return back();
    }

    public function reject(int $id)
    {
        ProductQuestion::findOrFail($id)->update(['status' => 'rejected']);

        return response()->json(['message' => 'Rejected.']);
    }

    public function destroy(int $id)
    {
        ProductQuestion::findOrFail($id)->delete();

        return response()->json(['message' => 'Deleted.']);
    }
}
