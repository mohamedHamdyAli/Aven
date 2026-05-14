<x-admin::layouts>
    <x-slot:title>@lang('size-guide::app.admin.size-guide.title')</x-slot>

    <div class="flex items-center justify-between mb-6">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('size-guide::app.admin.size-guide.title')
        </p>
        <a href="{{ route('admin.size-guide.create') }}"
           class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
            + @lang('size-guide::app.admin.size-guide.create')
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700 dark:bg-green-900/20 dark:text-green-400">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-800 text-left text-xs font-semibold uppercase text-gray-500">
                <tr>
                    <th class="px-5 py-3">@lang('size-guide::app.admin.size-guide.name')</th>
                    <th class="px-5 py-3">@lang('size-guide::app.admin.size-guide.gender')</th>
                    <th class="px-5 py-3">@lang('size-guide::app.admin.size-guide.type')</th>
                    <th class="px-5 py-3">@lang('size-guide::app.admin.size-guide.rows')</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($charts as $chart)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="px-5 py-3 font-medium text-gray-800 dark:text-white">{{ $chart->name }}</td>
                        <td class="px-5 py-3 capitalize text-gray-600 dark:text-gray-400">{{ $chart->gender }}</td>
                        <td class="px-5 py-3 capitalize text-gray-600 dark:text-gray-400">{{ $chart->type }}</td>
                        <td class="px-5 py-3 text-gray-600 dark:text-gray-400">{{ $chart->rows_count }}</td>
                        <td class="px-5 py-3 flex items-center gap-3 justify-end">
                            <a href="{{ route('admin.size-guide.edit', $chart->id) }}"
                               class="text-blue-600 hover:underline text-xs">@lang('size-guide::app.admin.size-guide.edit')</a>
                            <button class="delete-btn text-red-500 hover:underline text-xs"
                                    data-id="{{ $chart->id }}">
                                @lang('size-guide::app.admin.size-guide.delete')
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-gray-400">
                            @lang('size-guide::app.admin.size-guide.empty')
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('click', function(e) {
        if (!e.target.classList.contains('delete-btn')) return;
        if (!confirm('حذف هذا الجدول؟')) return;

        var id = e.target.dataset.id;
        window.axios.delete('/{{ config('app.admin_url', 'admin') }}/size-guide/' + id)
            .then(function() { location.reload(); });
    });
    </script>
    @endpush
</x-admin::layouts>
