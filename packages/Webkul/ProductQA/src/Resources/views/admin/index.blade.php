<x-admin::layouts>
    <x-slot:title>Product Q&A</x-slot>

    <div class="flex items-center justify-between gap-4">
        <p class="text-xl font-bold text-gray-800">Product Q&amp;A</p>
        <span class="text-sm text-gray-500">
            <span class="font-semibold text-yellow-600">{{ $questions->where('status', 'pending')->count() }}</span> pending answers
        </span>
    </div>

    <div class="mt-6 space-y-4">
        @forelse ($questions as $q)
            @php
                $statusColors = [
                    'pending'  => 'bg-yellow-100 text-yellow-700',
                    'approved' => 'bg-green-100 text-green-700',
                    'rejected' => 'bg-red-100 text-red-700',
                ];
            @endphp

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $statusColors[$q->status] }}">{{ ucfirst($q->status) }}</span>
                            <span class="text-xs text-gray-400">Product #{{ $q->product_id }} · {{ $q->customer_name }} · {{ $q->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="mt-2 font-medium text-gray-800">Q: {{ $q->question }}</p>

                        @if ($q->answer)
                            <p class="mt-1 text-sm text-gray-600">A: {{ $q->answer }}</p>
                        @endif
                    </div>

                    <div class="flex gap-2">
                        @if ($q->status !== 'rejected')
                            <button
                                type="button"
                                class="text-xs text-red-500 hover:text-red-700"
                                onclick="if(confirm('Reject this question?')) fetch('{{ route('admin.product_qa.reject', $q->id) }}',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'}}).then(()=>location.reload())"
                            >Reject</button>
                        @endif
                        <button
                            type="button"
                            class="text-xs text-red-400 hover:text-red-600"
                            onclick="if(confirm('Delete?')) fetch('{{ route('admin.product_qa.destroy', $q->id) }}',{method:'DELETE',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}}).then(()=>location.reload())"
                        >Delete</button>
                    </div>
                </div>

                @if ($q->status === 'pending' || $q->status === 'approved')
                    <form method="POST" action="{{ route('admin.product_qa.answer', $q->id) }}" class="mt-4 border-t border-gray-100 pt-4">
                        @csrf
                        <textarea name="answer" rows="2" required
                                  class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 outline-none"
                                  placeholder="Write your answer...">{{ $q->answer }}</textarea>
                        <div class="mt-2 flex items-center gap-3">
                            <label class="flex items-center gap-1.5 text-xs text-gray-600">
                                <input type="checkbox" name="is_published" value="1" {{ $q->is_published ? 'checked' : '' }}>
                                Publish publicly on product page
                            </label>
                            <button type="submit" class="primary-button py-1.5 text-xs">Save Answer</button>
                        </div>
                    </form>
                @endif
            </div>
        @empty
            <div class="rounded-xl border border-gray-200 bg-white py-16 text-center text-sm text-gray-400">
                No questions yet. They'll appear here when customers ask.
            </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $questions->links() }}</div>
</x-admin::layouts>
