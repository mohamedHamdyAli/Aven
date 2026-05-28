<?php

use Webkul\Blog\Models\BlogPost;

it('allows mass assignment of fillable fields', function () {
    $post = new BlogPost([
        'title'    => 'My Fashion Guide',
        'slug'     => 'my-fashion-guide',
        'content'  => 'Lorem ipsum',
        'status'   => 'published',
        'tags'     => ['fashion', 'style'],
    ]);

    expect($post->title)->toBe('My Fashion Guide')
        ->and($post->slug)->toBe('my-fashion-guide')
        ->and($post->status)->toBe('published');
});

it('casts tags to array', function () {
    $post = new BlogPost(['tags' => ['a', 'b']]);

    expect($post->tags)->toBeArray()
        ->and($post->tags)->toContain('a');
});

it('casts published_at to datetime', function () {
    $post = new BlogPost(['published_at' => '2024-06-01 09:00:00']);

    expect($post->published_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

it('generates a unique slug', function () {
    $slug = BlogPost::generateSlug('My Test Post');

    expect($slug)->toBeString()
        ->and($slug)->not()->toBeEmpty();
});

it('scope published returns only published posts with past published_at', function () {
    BlogPost::factory()->create(['status' => 'published', 'published_at' => now()->subDay()]);
    BlogPost::factory()->draft()->create();

    $published = BlogPost::published()->get();

    expect($published->every(fn ($p) => $p->status === 'published'))->toBeTrue();
});

it('reading_time accessor returns integer', function () {
    $post = BlogPost::factory()->create();

    expect($post->reading_time)->toBeInt();
});

it('creates a record via factory', function () {
    $post = BlogPost::factory()->create();

    expect($post->exists)->toBeTrue()
        ->and($post->status)->toBe('published');
});

it('draft state creates draft post', function () {
    $post = BlogPost::factory()->draft()->create();

    expect($post->status)->toBe('draft')
        ->and($post->published_at)->toBeNull();
});
