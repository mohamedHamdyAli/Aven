@extends('admin::layouts.master')

@section('title')
    Gift Cards
@endsection

@section('content')
    <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800 dark:text-white">Gift Cards</p>

        <a
            href="{{ route('admin.gift-cards.create') }}"
            class="primary-button"
        >
            Create Gift Cards
        </a>
    </div>

    <div class="mt-6 box-shadow rounded-xl bg-white dark:bg-gray-900">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="bg-gray-50 dark:bg-gray-700 text-xs text-gray-700 dark:text-gray-300 uppercase">
                <tr>
                    <th class="px-6 py-3">Code</th>
                    <th class="px-6 py-3">Initial Balance</th>
                    <th class="px-6 py-3">Used</th>
                    <th class="px-6 py-3">Remaining</th>
                    <th class="px-6 py-3">Recipient</th>
                    <th class="px-6 py-3">Expires</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($cards as $card)
                    <tr class="border-b dark:border-gray-700">
                        <td class="px-6 py-4 font-mono font-bold text-gray-900 dark:text-white">{{ $card->code }}</td>
                        <td class="px-6 py-4">{{ core()->formatPrice($card->initial_balance) }}</td>
                        <td class="px-6 py-4">{{ core()->formatPrice($card->used_amount) }}</td>
                        <td class="px-6 py-4 font-semibold {{ $card->remaining_balance > 0 ? 'text-green-600' : 'text-gray-400' }}">
                            {{ core()->formatPrice($card->remaining_balance) }}
                        </td>
                        <td class="px-6 py-4">
                            @if ($card->recipient_email)
                                <div>{{ $card->recipient_name ?? '-' }}</div>
                                <div class="text-xs text-gray-400">{{ $card->recipient_email }}</div>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">{{ $card->expires_at?->format('d M Y') ?? 'Never' }}</td>
                        <td class="px-6 py-4">
                            @if (! $card->is_active)
                                <span class="rounded-full bg-gray-100 text-gray-500 px-2 py-0.5 text-xs">Inactive</span>
                            @elseif ($card->remaining_balance <= 0)
                                <span class="rounded-full bg-gray-100 text-gray-500 px-2 py-0.5 text-xs">Redeemed</span>
                            @elseif ($card->expires_at && $card->expires_at->isPast())
                                <span class="rounded-full bg-red-100 text-red-600 px-2 py-0.5 text-xs">Expired</span>
                            @else
                                <span class="rounded-full bg-green-100 text-green-700 px-2 py-0.5 text-xs font-semibold">Active</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <form
                                method="POST"
                                action="{{ route('admin.gift-cards.destroy', $card->id) }}"
                                onsubmit="return confirm('Delete this gift card?')"
                            >
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline text-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-400">No gift cards yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4">
            {{ $cards->links() }}
        </div>
    </div>
@endsection
