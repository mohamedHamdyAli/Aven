<x-shop::layouts.index :title="$customer->first_name . '\'s Wishlist'">
    <div class="container mx-auto px-4 py-10">
        <h1 class="mb-6 text-2xl font-bold text-navyBlue">{{ $customer->first_name }}'s Wishlist</h1>

        @if ($items->count() === 0)
            <p class="text-gray-500">This wishlist is empty.</p>
        @else
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
                @foreach ($items as $item)
                    <a href="{{ url('/' . $item->url_key) }}" class="group block overflow-hidden rounded-lg border border-gray-100 p-3 transition hover:shadow-md">
                        @php $img = \Illuminate\Support\Facades\DB::table('product_images')->where('product_id', $item->product_id)->orderBy('id')->value('path'); @endphp
                        @if ($img)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($img) }}" alt="{{ $item->name }}" class="mb-2 h-36 w-full rounded object-contain"/>
                        @endif
                        <p class="line-clamp-2 text-sm font-medium text-gray-800">{{ $item->name }}</p>
                        <p class="mt-1 font-bold text-navyBlue">{{ core()->currency($item->price) }}</p>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-shop::layouts.index>
