<x-admin::layouts>
    <x-slot:title>AI Support — Knowledge Base</x-slot:title>

    <div class="flex items-center justify-between mb-5">
        <p class="text-xl font-bold text-gray-800">Knowledge Base</p>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
    @endif

    {{-- Add new entry form --}}
    <div class="bg-white rounded-lg shadow p-5 mb-6">
        <p class="font-semibold text-gray-700 mb-4">Add New Entry</p>
        <form method="POST" action="{{ route('admin.ai-support.knowledge-base.store') }}">
            @csrf
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Question / Topic</label>
                    <input type="text" name="question" required
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                        placeholder="e.g. What is your return policy?">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Answer</label>
                    <textarea name="answer" rows="4" required
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                        placeholder="The full answer the AI will use..."></textarea>
                </div>
                <div class="flex gap-4 items-center">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" value="0" min="0"
                            class="w-24 border border-gray-300 rounded px-3 py-2 text-sm">
                    </div>
                    <div class="flex items-center gap-2 mt-4">
                        <input type="checkbox" name="is_active" value="1" id="is_active" checked>
                        <label for="is_active" class="text-sm text-gray-700">Active</label>
                    </div>
                </div>
            </div>
            <button type="submit" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded text-sm hover:bg-indigo-700">
                Add Entry
            </button>
        </form>
    </div>

    {{-- DataGrid --}}
    <x-admin::datagrid :src="route('admin.ai-support.knowledge-base.index')" />
</x-admin::layouts>
