@extends('admin::layouts.master')

@section('title')
    Create Gift Cards
@endsection

@section('content')
    <div class="flex gap-4 justify-between items-center">
        <p class="text-xl font-bold text-gray-800 dark:text-white">Create Gift Cards</p>
    </div>

    <div class="mt-6 box-shadow rounded-xl bg-white dark:bg-gray-900 p-6 max-w-xl">
        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.gift-cards.store') }}">
            @csrf

            <div class="mb-4 grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Balance per Card</label>
                    <input
                        type="number"
                        name="initial_balance"
                        value="{{ old('initial_balance') }}"
                        step="0.01"
                        min="1"
                        class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-navyBlue dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                        required
                    />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quantity to Create</label>
                    <input
                        type="number"
                        name="quantity"
                        value="{{ old('quantity', 1) }}"
                        min="1"
                        max="100"
                        class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-navyBlue dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                        required
                    />
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Expiry Date <span class="text-gray-400">(optional)</span></label>
                <input
                    type="date"
                    name="expires_at"
                    value="{{ old('expires_at') }}"
                    class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-navyBlue dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                />
            </div>

            <div class="mb-4 grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Recipient Name <span class="text-gray-400">(optional)</span></label>
                    <input
                        type="text"
                        name="recipient_name"
                        value="{{ old('recipient_name') }}"
                        class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-navyBlue dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                    />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Recipient Email <span class="text-gray-400">(optional)</span></label>
                    <input
                        type="email"
                        name="recipient_email"
                        value="{{ old('recipient_email') }}"
                        class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-navyBlue dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                    />
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gift Message <span class="text-gray-400">(optional)</span></label>
                <textarea
                    name="message"
                    rows="3"
                    class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-navyBlue dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                >{{ old('message') }}</textarea>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="primary-button">Create Gift Cards</button>
                <a href="{{ route('admin.gift-cards.index') }}" class="secondary-button">Cancel</a>
            </div>
        </form>
    </div>
@endsection
