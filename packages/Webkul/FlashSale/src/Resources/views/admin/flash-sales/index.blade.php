@extends('admin::layouts.master')

@section('title')
    Flash Sales
@endsection

@section('content')
    <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800 dark:text-white">Flash Sales</p>

        <a
            href="{{ route('admin.marketing.flash-sales.create') }}"
            class="primary-button"
        >
            Create Flash Sale
        </a>
    </div>

    <div class="mt-6 box-shadow rounded-xl bg-white dark:bg-gray-900">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="bg-gray-50 dark:bg-gray-700 text-xs text-gray-700 dark:text-gray-300 uppercase">
                <tr>
                    <th class="px-6 py-3">Name</th>
                    <th class="px-6 py-3">Discount</th>
                    <th class="px-6 py-3">Products</th>
                    <th class="px-6 py-3">Starts At</th>
                    <th class="px-6 py-3">Ends At</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sales as $sale)
                    <tr class="border-b dark:border-gray-700">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $sale->name }}</td>
                        <td class="px-6 py-4">{{ $sale->discount_percent }}%</td>
                        <td class="px-6 py-4">{{ $sale->products_count }}</td>
                        <td class="px-6 py-4">{{ $sale->starts_at->format('d M Y H:i') }}</td>
                        <td class="px-6 py-4">{{ $sale->ends_at->format('d M Y H:i') }}</td>
                        <td class="px-6 py-4">
                            @if ($sale->active)
                                <span class="rounded-full bg-green-100 text-green-700 px-2 py-0.5 text-xs font-semibold">Active</span>
                            @elseif (now() > $sale->ends_at)
                                <span class="rounded-full bg-gray-100 text-gray-500 px-2 py-0.5 text-xs">Expired</span>
                            @else
                                <span class="rounded-full bg-yellow-100 text-yellow-700 px-2 py-0.5 text-xs">Scheduled</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <form
                                method="POST"
                                action="{{ route('admin.marketing.flash-sales.destroy', $sale->id) }}"
                                onsubmit="return confirm('Delete this flash sale?')"
                            >
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline text-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-400">No flash sales yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4">
            {{ $sales->links() }}
        </div>
    </div>
@endsection
