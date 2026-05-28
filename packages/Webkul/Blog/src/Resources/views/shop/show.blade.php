@extends('shop::layouts.master')

@section('page_title', $post->meta_title ?: $post->title)

@push('meta')
    @if ($post->meta_description)
        <meta name="description" content="{{ $post->meta_description }}">
    @endif
    <meta property="og:title"       content="{{ $post->meta_title ?: $post->title }}">
    <meta property="og:description" content="{{ $post->meta_description ?: $post->excerpt }}">
    @if ($post->featured_image)
        <meta property="og:image" content="{{ Storage::url($post->featured_image) }}">
    @endif
    <meta property="og:type" content="article">
@endpush

@section('content-wrapper')
<div class="container mx-auto px-4 py-10 max-w-4xl">

    {{-- Breadcrumb --}}
    <nav class="mb-6 text-sm text-gray-400">
        <a href="{{ route('shop.blog.index') }}" class="hover:text-indigo-600">Blog</a>
        @if ($post->category)
            <span class="mx-2">/</span>
            <a href="{{ route('shop.blog.index', ['category' => $post->category]) }}" class="hover:text-indigo-600">{{ $post->category }}</a>
        @endif
        <span class="mx-2">/</span>
        <span class="text-gray-600">{{ Str::limit($post->title, 50) }}</span>
    </nav>

    {{-- Hero Image --}}
    @if ($post->featured_image)
        <img src="{{ Storage::url($post->featured_image) }}"
             alt="{{ $post->title }}"
             class="mb-8 w-full rounded-2xl object-cover shadow-md" style="max-height:420px">
    @endif

    {{-- Meta --}}
    <div class="mb-4 flex flex-wrap items-center gap-3 text-sm text-gray-400">
        @if ($post->category)
            <a href="{{ route('shop.blog.index', ['category' => $post->category]) }}"
               class="rounded-full bg-indigo-50 px-3 py-0.5 text-xs font-semibold text-indigo-600 hover:bg-indigo-100">
                {{ $post->category }}
            </a>
        @endif
        <span>{{ $post->published_at?->format('d M Y') }}</span>
        <span>·</span>
        <span>{{ $post->reading_time }} min read</span>
    </div>

    {{-- Title --}}
    <h1 class="mb-6 text-4xl font-bold leading-tight text-gray-900">{{ $post->title }}</h1>

    {{-- Tags --}}
    @if ($post->tags)
        <div class="mb-6 flex flex-wrap gap-2">
            @foreach ($post->tags as $tag)
                <span class="rounded-full bg-gray-100 px-3 py-0.5 text-xs text-gray-500">#{{ $tag }}</span>
            @endforeach
        </div>
    @endif

    {{-- Content --}}
    <div class="prose prose-gray max-w-none prose-headings:font-bold prose-a:text-indigo-600">
        {!! nl2br(e($post->content)) !!}
    </div>

    {{-- Related Posts --}}
    @if ($related->isNotEmpty())
        <div class="mt-16 border-t border-gray-100 pt-10">
            <h2 class="mb-6 text-xl font-bold text-gray-900">Related Posts</h2>
            <div class="grid gap-6 sm:grid-cols-3">
                @foreach ($related as $rel)
                    <a href="{{ route('shop.blog.show', $rel->slug) }}"
                       class="group flex flex-col gap-2 rounded-xl border border-gray-100 bg-white p-4 hover:shadow-md transition-shadow">
                        @if ($rel->featured_image)
                            <img src="{{ Storage::url($rel->featured_image) }}"
                                 class="h-32 w-full rounded-lg object-cover">
                        @endif
                        <p class="text-sm font-semibold text-gray-800 group-hover:text-indigo-600 transition-colors line-clamp-2">
                            {{ $rel->title }}
                        </p>
                        <p class="text-xs text-gray-400">{{ $rel->published_at?->format('d M Y') }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
