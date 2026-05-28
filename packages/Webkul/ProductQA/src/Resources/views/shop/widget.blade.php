<div class="mt-8 rounded-2xl border border-gray-200 bg-white p-6" v-pre x-data="qaWidget({{ $productId }})">
    <h2 class="mb-5 text-lg font-bold text-gray-800">Questions &amp; Answers</h2>

    <!-- Existing Q&As -->
    <div class="mb-6 space-y-4">
        <template x-if="loading">
            <div class="space-y-3">
                <template x-for="i in 2">
                    <div class="animate-pulse h-16 rounded-lg bg-gray-100"></div>
                </template>
            </div>
        </template>

        <template x-if="!loading && questions.length === 0">
            <p class="text-sm text-gray-400">No questions yet. Be the first to ask!</p>
        </template>

        <template x-for="q in questions" :key="q.id">
            <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">
                <p class="text-sm font-medium text-gray-800">Q: <span x-text="q.question"></span></p>
                <p class="mt-1 text-sm text-gray-600" x-show="q.answer">A: <span x-text="q.answer"></span></p>
                <p class="mt-1 text-xs text-gray-400" x-text="q.customer_name + ' · ' + formatDate(q.created_at)"></p>
            </div>
        </template>
    </div>

    <!-- Ask a Question Form -->
    <div class="border-t border-gray-100 pt-5">
        <h3 class="mb-3 text-sm font-semibold text-gray-700">Ask a Question</h3>

        <template x-if="submitted">
            <div class="rounded-lg bg-green-50 p-3 text-sm text-green-700">
                ✓ Your question has been submitted. We'll answer it soon!
            </div>
        </template>

        <form x-show="!submitted" @submit.prevent="submitQuestion" class="space-y-3">
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <input type="text" x-model="form.customer_name" required placeholder="Your name"
                       class="rounded-lg border border-gray-300 px-3 py-2 text-sm outline-none focus:border-indigo-500">
                <input type="email" x-model="form.customer_email" required placeholder="Your email"
                       class="rounded-lg border border-gray-300 px-3 py-2 text-sm outline-none focus:border-indigo-500">
            </div>
            <textarea x-model="form.question" required rows="2" placeholder="Your question..."
                      class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm outline-none focus:border-indigo-500"></textarea>
            <button type="submit" :disabled="submitting"
                    class="rounded-lg bg-navyBlue px-5 py-2 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-50">
                <span x-text="submitting ? 'Submitting...' : 'Submit Question'"></span>
            </button>
        </form>
    </div>
</div>

<script>
function qaWidget(productId) {
    return {
        productId,
        questions: [],
        loading: true,
        submitted: false,
        submitting: false,
        form: { customer_name: '', customer_email: '', question: '' },

        init() {
            fetch(`/product-qa/${productId}/questions`)
                .then(r => r.json())
                .then(data => { this.questions = data; this.loading = false; })
                .catch(() => { this.loading = false; });
        },

        submitQuestion() {
            this.submitting = true;
            const fd = new FormData();
            fd.append('product_id', this.productId);
            fd.append('customer_name', this.form.customer_name);
            fd.append('customer_email', this.form.customer_email);
            fd.append('question', this.form.question);
            fd.append('_token', document.querySelector('meta[name="csrf-token"]')?.content ?? '');

            fetch('/product-qa/ask', { method: 'POST', body: fd })
                .then(r => r.json())
                .then(() => { this.submitted = true; })
                .catch(() => { alert('Failed to submit. Please try again.'); })
                .finally(() => { this.submitting = false; });
        },

        formatDate(d) {
            return new Date(d).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
        },
    };
}
</script>
