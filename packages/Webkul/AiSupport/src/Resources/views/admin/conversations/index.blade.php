<x-admin::layouts>
    <x-slot:title>AI Support — Conversations</x-slot:title>

    <div class="flex items-center justify-between mb-5">
        <p class="text-xl font-bold text-gray-800">AI Support Conversations</p>
    </div>

    <x-admin::datagrid :src="route('admin.ai-support.conversations.index')" />
</x-admin::layouts>
