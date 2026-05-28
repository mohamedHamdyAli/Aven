@extends('shop::layouts.master')

@section('page_title', 'My Store Credit')

@section('content-wrapper')
<div class="container mx-auto max-w-4xl px-4 py-10">

    <h1 class="mb-6 text-2xl font-bold text-gray-900">Store Credit</h1>

    {{-- Balance --}}
    <div class="mb-8 rounded-2xl bg-gradient-to-r from-indigo-500 to-purple-600 p-6 text-white shadow">
        <p class="text-sm opacity-80">Available Balance</p>
        <p class="mt-1 text-4xl font-bold">{{ core()->formatPrice($balance) }}</p>
        <p class="mt-2 text-xs opacity-70">Credit is automatically applied at checkout when you choose to use it.</p>
    </div>

    {{-- Transactions --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
        <div class="border-b border-gray-100 px-5 py-4">
            <p class="font-semibold text-gray-800">Transaction History</p>
        </div>
        @if ($transactions->isEmpty())
            <p class="py-12 text-center text-sm text-gray-400">No transactions yet.</p>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50 text-xs text-gray-500">
                        <th class="px-4 py-3 text-left">Date</th>
                        <th class="px-4 py-3 text-left">Description</th>
                        <th class="px-4 py-3 text-right">Amount</th>
                        <th class="px-4 py-3 text-right">Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $tx)
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-4 py-3 text-xs text-gray-400">{{ $tx->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $tx->note ?? ucfirst($tx->type) }}</td>
                            <td class="px-4 py-3 text-right font-semibold {{ $tx->type === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $tx->type === 'credit' ? '+' : '-' }}{{ core()->formatPrice($tx->amount) }}
                            </td>
                            <td class="px-4 py-3 text-right text-gray-500">{{ core()->formatPrice($tx->balance_after) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-4">{{ $transactions->links() }}</div>
        @endif
    </div>
</div>
@endsection
