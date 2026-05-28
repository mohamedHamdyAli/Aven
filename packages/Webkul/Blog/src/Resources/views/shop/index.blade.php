@extends('shop::layouts.master')

@section('page_title', __('Blog'))

@section('content-wrapper')
<div class="container mx-auto px-4 py-10 max-w-7xl">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Blog</h1>
        <p class="mt-1 text-gray-500">Tips, news and updates from our team.</p>
    </div>

    {{-- Category Filter --}}
    @if ($categories->isNotEmpty())
        <div class="mb-8 flex flex-wrap gap-2">
            <a href="{{ route('shop.blog.index') }}"
               class="rounded-full px-4 py-1.5 text-sm font-medium transition
                      {{ !request('category') ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                All
            </a>
            @foreach ($categories as $cat)
                <a href="{{ route('shop.blog.index', ['category' => $cat]) }}"
                   class="rounded-full px-4 py-1.5 text-sm font-medium transition
                          {{ request('category') === $cat ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>
    @endif

    {{-- Posts Grid --}}
    @if ($posts->isEmpty())
        <div class="py-20 text-center text-gray-400">No posts published yet.</div>
    @else
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($posts as $post)
                <article class="group flex flex-col overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm hover:shadow-md transition-shadow">
                    @if ($post->featured_image)
                        <a href="{{ route('shop.blog.show', $post->slug) }}" class="block overflow-hidden">
                            <img src="{{ Storage::url($post->featured_image) }}"
                                 alt="{{ $post->title }}"
                                 class="h-48 w-full object-cover transition-transform duration-300 group-hover:scale-105">
                        </a>
                    @endif
                    <div class="flex flex-1 flex-col p-5">
                        @if ($post->category)
                            <span class="mb-2 inline-block w-fit rounded-full bg-indigo-50 px-3 py-0.5 text-xs font-semibold text-indigo-600">
                                {{ $post->category }}
                            </span>
                        @endif
                        <h2 class="mb-2 text-lg font-bold text-gray-900 leading-snug">
                            <a href="{{ route('shop.blog.show', $post->slug) }}" class="hover:text-indigo-600 transition-colors">
                                {{ $post->title }}
                            </a>
                        </h2>
                        @if ($post->excerpt)
                            <p class="mb-4 flex-1 text-sm text-gray-500 line-clamp-3">{{ $post->excerpt }}</p>
                        @endif
                        <div class="mt-auto flex items-center justify-between text-xs text-gray-400">
                            <span>{{ $post->published_at?->format('d M Y') }}</span>
                            <span>{{ $post->reading_time }} min read</span>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-10">{{ $posts->links() }}</div>
    @endif
</div>
@endsection
