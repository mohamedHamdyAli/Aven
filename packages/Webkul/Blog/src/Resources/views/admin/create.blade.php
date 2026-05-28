<x-admin::layouts>
    <x-slot:title>{{ $post ? 'Edit Post' : 'New Post' }}</x-slot>

    <div class="flex items-center justify-between gap-4">
        <p class="text-xl font-bold text-gray-800">{{ $post ? 'Edit Post' : 'New Post' }}</p>
        <a href="{{ route('admin.blog.index') }}" class="secondary-button">← Back</a>
    </div>

    @if ($errors->any())
        <div class="mt-4 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-disc pl-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ $post ? route('admin.blog.update', $post->id) : route('admin.blog.store') }}"
          enctype="multipart/form-data"
          class="mt-6 space-y-6">
        @csrf
        @if ($post) @method('PUT') @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- Main Column --}}
            <div class="space-y-6 lg:col-span-2">

                {{-- Title --}}
                <div class="rounded-xl border border-gray-200 bg-white p-5">
                    <label class="mb-1 block text-sm font-semibold text-gray-700">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $post?->title) }}"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none"
                           required>
                </div>

                {{-- Content --}}
                <div class="rounded-xl border border-gray-200 bg-white p-5">
                    <label class="mb-1 block text-sm font-semibold text-gray-700">Content <span class="text-red-500">*</span></label>
                    <textarea name="content" id="blog-content" rows="18"
                              class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none font-mono">{{ old('content', $post?->content) }}</textarea>
                </div>

                {{-- Excerpt --}}
                <div class="rounded-xl border border-gray-200 bg-white p-5">
                    <label class="mb-1 block text-sm font-semibold text-gray-700">Excerpt</label>
                    <textarea name="excerpt" rows="3"
                              class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none"
                              maxlength="500">{{ old('excerpt', $post?->excerpt) }}</textarea>
                    <p class="mt-1 text-xs text-gray-400">Short summary shown in blog listing (max 500 chars)</p>
                </div>

                {{-- SEO --}}
                <div class="rounded-xl border border-gray-200 bg-white p-5">
                    <p class="mb-4 text-sm font-semibold text-gray-700">SEO</p>
                    <div class="space-y-3">
                        <div>
                            <label class="mb-1 block text-xs text-gray-500">Meta Title</label>
                            <input type="text" name="meta_title" value="{{ old('meta_title', $post?->meta_title) }}"
                                   class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none"
                                   maxlength="200">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs text-gray-500">Meta Description</label>
                            <textarea name="meta_description" rows="2"
                                      class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none"
                                      maxlength="300">{{ old('meta_description', $post?->meta_description) }}</textarea>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">

                {{-- Publish --}}
                <div class="rounded-xl border border-gray-200 bg-white p-5">
                    <p class="mb-4 text-sm font-semibold text-gray-700">Publish</p>
                    <div class="mb-4">
                        <label class="mb-1 block text-xs text-gray-500">Status</label>
                        <select name="status"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none">
                            <option value="draft"     {{ old('status', $post?->status) === 'draft'     ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $post?->status) === 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                    </div>
                    <button type="submit" class="primary-button w-full justify-center">
                        {{ $post ? 'Update Post' : 'Create Post' }}
                    </button>
                </div>

                {{-- Featured Image --}}
                <div class="rounded-xl border border-gray-200 bg-white p-5">
                    <p class="mb-3 text-sm font-semibold text-gray-700">Featured Image</p>
                    @if ($post?->featured_image)
                        <img src="{{ Storage::url($post->featured_image) }}"
                             class="mb-3 w-full rounded-lg object-cover" style="max-height:160px">
                    @endif
                    <input type="file" name="featured_image" accept="image/*"
                           class="w-full text-sm text-gray-500 file:mr-3 file:rounded file:border-0 file:bg-indigo-50 file:px-3 file:py-1 file:text-xs file:text-indigo-700">
                    <p class="mt-1 text-xs text-gray-400">JPG, PNG, WebP · max 2 MB</p>
                </div>

                {{-- Category & Tags --}}
                <div class="rounded-xl border border-gray-200 bg-white p-5">
                    <p class="mb-4 text-sm font-semibold text-gray-700">Categorization</p>
                    <div class="space-y-3">
                        <div>
                            <label class="mb-1 block text-xs text-gray-500">Category</label>
                            <input type="text" name="category" value="{{ old('category', $post?->category) }}"
                                   class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none"
                                   maxlength="100">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs text-gray-500">Tags</label>
                            <input type="text" name="tags"
                                   value="{{ old('tags', $post ? implode(', ', (array) $post->tags) : '') }}"
                                   class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none"
                                   placeholder="news, tutorial, tips">
                            <p class="mt-1 text-xs text-gray-400">Comma-separated</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
</x-admin::layouts>
