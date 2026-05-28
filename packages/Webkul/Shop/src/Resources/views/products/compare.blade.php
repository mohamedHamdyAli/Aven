<x-shop::layouts.index :title="'Compare Products'">
    <div class="container mx-auto px-4 py-8">
        <h1 class="mb-6 text-2xl font-bold text-navyBlue">Compare Products</h1>

        @if (count($products) === 0)
            <div class="py-20 text-center">
                <p class="mb-4 text-gray-500">No products selected for comparison.</p>
                <a href="{{ route('shop.home.index') }}" class="rounded bg-navyBlue px-6 py-2 text-white hover:bg-blue-800">
                    Browse Products
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-sm">
                    <thead>
                        <tr>
                            <th class="w-32 border-b p-3 text-left text-gray-500">Feature</th>
                            @foreach ($products as $p)
                                <th class="border-b p-3 text-center">
                                    <form action="{{ route('shop.comparison.remove') }}" method="POST" class="mb-2">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $p->product_id }}">
                                        <button type="submit" class="text-xs text-red-400 hover:underline">&#x2715; Remove</button>
                                    </form>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-gray-50">
                            <td class="border-b p-3 font-medium text-gray-600">Image</td>
                            @foreach ($products as $p)
                                <td class="border-b p-3 text-center">
                                    @php $img = \Illuminate\Support\Facades\DB::table('product_images')->where('product_id', $p->product_id)->orderBy('id')->value('path'); @endphp
                                    @if ($img)
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($img) }}" alt="{{ $p->name }}" class="mx-auto h-28 w-28 object-contain"/>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        <tr>
                            <td class="border-b p-3 font-medium text-gray-600">Name</td>
                            @foreach ($products as $p)
                                <td class="border-b p-3 text-center">
                                    <a href="{{ url('/' . $p->url_key) }}" class="font-medium text-navyBlue hover:underline">{{ $p->name }}</a>
                                </td>
                            @endforeach
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="border-b p-3 font-medium text-gray-600">Price</td>
                            @foreach ($products as $p)
                                <td class="border-b p-3 text-center font-bold text-navyBlue">
                                    {{ core()->currency($p->price) }}
                                </td>
                            @endforeach
                        </tr>
                        <tr>
                            <td class="border-b p-3 font-medium text-gray-600">Description</td>
                            @foreach ($products as $p)
                                <td class="border-b p-3 text-center text-gray-600">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($p->short_description ?? ''), 100) }}
                                </td>
                            @endforeach
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="border-b p-3 font-medium text-gray-600">Action</td>
                            @foreach ($products as $p)
                                <td class="border-b p-3 text-center">
                                    <a href="{{ url('/' . $p->url_key) }}" class="rounded bg-navyBlue px-4 py-2 text-sm text-white hover:bg-blue-800">
                                        View Product
                                    </a>
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-6 text-right">
                <form action="{{ route('shop.comparison.clear') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-sm text-red-500 hover:underline">Clear All</button>
                </form>
            </div>
        @endif
    </div>
</x-shop::layouts.index>
