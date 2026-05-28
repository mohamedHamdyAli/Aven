<?php

namespace Webkul\ProductQA\Http\Controllers\Shop;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\ProductQA\Models\ProductQuestion;

class ProductQAController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id'    => 'required|exists:products,id',
            'customer_name' => 'required|string|max:100',
            'customer_email'=> 'required|email|max:150',
            'question'      => 'required|string|max:1000',
        ]);

        $customer = auth()->guard('customer')->user();

        ProductQuestion::create([
            'product_id'     => $data['product_id'],
            'customer_id'    => $customer?->id,
            'customer_name'  => $data['customer_name'],
            'customer_email' => $data['customer_email'],
            'question'       => $data['question'],
            'status'         => 'pending',
            'is_published'   => false,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Your question has been submitted. We will answer it soon!']);
        }

        session()->flash('success', 'Your question has been submitted. We will answer it soon!');

        return back();
    }

    public function forProduct(int $productId)
    {
        $questions = ProductQuestion::published()
            ->where('product_id', $productId)
            ->orderByDesc('created_at')
            ->get(['id', 'customer_name', 'question', 'answer', 'created_at']);

        return response()->json($questions);
    }
}
