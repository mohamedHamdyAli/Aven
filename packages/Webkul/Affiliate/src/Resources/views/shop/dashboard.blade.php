@extends('shop::layouts.master')

@section('page_title', 'Affiliate Dashboard')

@section('content-wrapper')
<div class="container mx-auto max-w-4xl px-4 py-10">
    <h1 class="mb-6 text-2xl font-bold text-gray-900">Affiliate Dashboard</h1>

    @if (session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    {{-- Status Banner --}}
    @if ($affiliate->status === 'pending')
        <div class="mb-6 rounded-xl border border-yellow-200 bg-yellow-50 p-4 text-sm text-yellow-800">
            Your application is under review. You'll receive an email once approved.
        </div>
    @elseif ($affiliate->status === 'rejected')
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
            Your affiliate application was rejected. Contact support for details.
        </div>
    @endif

    {{-- Stats --}}
    <div class="mb-8 grid grid-cols-2 gap-4 sm:grid-cols-4">
        <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
            <p class="text-xs text-gray-500">Commission Rate</p>
            <p class="mt-1 text-2xl font-bold text-indigo-600">{{ $affiliate->commission_rate }}%</p>
        </div>
        <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
            <p class="text-xs text-gray-500">Total Earned</p>
            <p class="mt-1 text-2xl font-bold text-green-600">{{ core()->formatPrice($affiliate->total_earned) }}</p>
        </div>
        <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
            <p class="text-xs text-gray-500">Total Paid</p>
            <p class="mt-1 text-2xl font-bold text-gray-600">{{ core()->formatPrice($affiliate->total_paid) }}</p>
        </div>
        <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
            <p class="text-xs text-gray-500">Pending</p>
            <p class="mt-1 text-2xl font-bold text-orange-500">{{ core()->formatPrice($affiliate->pendingBalance()) }}</p>
        </div>
    </div>

    @if ($affiliate->status === 'approved')
        {{-- Tracking Link --}}
        <div class="mb-8 rounded-xl border border-indigo-100 bg-indigo-50 p-5">
            <p class="mb-2 text-sm font-semibold text-gray-700">Your Affiliate Link</p>
            <div class="flex items-center gap-2">
                <input type="text" value="{{ url('/ref/' . $affiliate->code) }}" readonly
                       class="flex-1 rounded-lg border border-indigo-200 bg-white px-3 py-2 text-sm font-mono text-indigo-700"
                       id="aff-link">
                <button onclick="navigator.clipboard.writeText(document.getElementById('aff-link').value).then(()=>this.textContent='Copied!')"
                        class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 transition">
                    Copy
                </button>
            </div>
        </div>
    @endif

    {{-- Commissions --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
        <div class="border-b border-gray-100 px-5 py-4">
            <p class="font-semibold text-gray-800">Commission History</p>
        </div>
        @if ($commissions->isEmpty())
            <p class="py-12 text-center text-sm text-gray-400">No commissions yet. Start sharing your link!</p>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50 text-xs text-gray-500">
                        <th class="px-4 py-3 text-left">Order</th>
                        <th class="px-4 py-3 text-right">Commission</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-right">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($commissions as $c)
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-600">#{{ $c->order_id }}</td>
                            <td class="px-4 py-3 text-right font-semibold text-green-700">{{ core()->formatPrice($c->commission) }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="rounded-full px-2 py-0.5 text-xs font-semibold
                                    {{ $c->status === 'paid' ? 'bg-green-100 text-green-700' :
                                       ($c->status === 'approved' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700') }}">
                                    {{ ucfirst($c->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right text-xs text-gray-400">{{ $c->created_at->format('d M Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-4">{{ $commissions->links() }}</div>
        @endif
    </div>
</div>
@endsection
