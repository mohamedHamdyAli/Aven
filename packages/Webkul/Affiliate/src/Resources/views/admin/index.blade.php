<x-admin::layouts>
    <x-slot:title>Affiliates</x-slot>

    <div class="flex items-center justify-between gap-4">
        <p class="text-xl font-bold text-gray-800">Affiliates</p>
    </div>

    @if (session('success'))
        <div class="mt-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <div class="mt-6 overflow-hidden rounded-xl border border-gray-200 bg-white">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50 text-xs text-gray-500">
                    <th class="px-4 py-3 text-left">Name / Email</th>
                    <th class="px-4 py-3 text-left">Code</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-right">Rate</th>
                    <th class="px-4 py-3 text-right">Earned</th>
                    <th class="px-4 py-3 text-right">Clicks</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($affiliates as $a)
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-800">{{ $a->name }}</p>
                            <p class="text-xs text-gray-400">{{ $a->email }}</p>
                        </td>
                        <td class="px-4 py-3 font-mono text-xs text-indigo-700">{{ $a->code }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="rounded-full px-2 py-0.5 text-xs font-semibold
                                {{ $a->status === 'approved' ? 'bg-green-100 text-green-700' :
                                   ($a->status === 'pending'  ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-600') }}">
                                {{ ucfirst($a->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right text-gray-600">{{ $a->commission_rate }}%</td>
                        <td class="px-4 py-3 text-right font-medium text-green-700">{{ number_format($a->total_earned, 2) }}</td>
                        <td class="px-4 py-3 text-right text-gray-500">{{ $a->clicks_count }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.affiliates.show', $a->id) }}" class="mr-2 text-xs text-indigo-600 hover:underline">View</a>
                            @if ($a->status === 'pending')
                                <form method="POST" action="{{ route('admin.affiliates.approve', $a->id) }}" class="inline">
                                    @csrf
                                    <button class="mr-1 text-xs text-green-600 hover:underline">Approve</button>
                                </form>
                                <form method="POST" action="{{ route('admin.affiliates.reject', $a->id) }}" class="inline">
                                    @csrf
                                    <button class="text-xs text-red-500 hover:underline">Reject</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-16 text-center text-sm text-gray-400">No affiliates yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $affiliates->links() }}</div>
</x-admin::layouts>
