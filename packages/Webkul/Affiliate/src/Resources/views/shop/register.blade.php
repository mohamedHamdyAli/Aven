@extends('shop::layouts.master')

@section('page_title', 'Become an Affiliate')

@section('content-wrapper')
<div class="container mx-auto max-w-lg px-4 py-12">
    <h1 class="mb-2 text-3xl font-bold text-gray-900">Join our Affiliate Program</h1>
    <p class="mb-8 text-gray-500">Earn commission for every sale you refer. Apply below and we'll review your application.</p>

    @if ($errors->any())
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            @foreach ($errors->all() as $e) <p>{{ $e }}</p> @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('shop.affiliate.apply') }}" class="space-y-4 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
        @csrf
        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Full Name</label>
            <input type="text" name="name" value="{{ old('name', $customer?->first_name . ' ' . $customer?->last_name) }}"
                   class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none" required>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" value="{{ old('email', $customer?->email) }}"
                   class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none" required>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Tell us about your platform / audience</label>
            <textarea name="notes" rows="3"
                      class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none">{{ old('notes') }}</textarea>
        </div>
        <button type="submit" class="w-full rounded-lg bg-indigo-600 py-3 text-sm font-semibold text-white hover:bg-indigo-700 transition">
            Submit Application
        </button>
    </form>
</div>
@endsection
