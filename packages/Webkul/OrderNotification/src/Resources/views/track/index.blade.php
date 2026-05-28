<x-shop::layouts>
    <x-slot:title>Track Your Order</x-slot>

    <div class="container mx-auto px-4 py-12 max-w-lg">
        <h1 class="mb-2 text-2xl font-bold text-gray-900">Track Your Order</h1>
        <p class="mb-8 text-sm text-gray-500">Enter your order number and email address to see the latest status.</p>

        @if ($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('shop.order.track.result') }}" class="space-y-5">
            @csrf
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700">Order Number <span class="text-red-500">*</span></label>
                <input type="text" name="order_id" value="{{ old('order_id') }}" required
                       placeholder="e.g. 100000001"
                       class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700">Email Address <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       placeholder="The email used when ordering"
                       class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none">
            </div>

            <button type="submit"
                    class="w-full rounded-xl bg-navyBlue px-6 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition-colors">
                Track Order
            </button>
        </form>
    </div>
</x-shop::layouts>
