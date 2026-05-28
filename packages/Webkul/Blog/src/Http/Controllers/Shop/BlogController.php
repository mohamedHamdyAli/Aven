<?php

namespace Webkul\Blog\Http\Controllers\Shop;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\Blog\Models\BlogPost;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::published()->orderByDesc('published_at');

        if ($request->category) {
            $query->where('category', $request->category);
        }

        $posts      = $query->paginate(12);
        $categories = BlogPost::published()->whereNotNull('category')
                               ->distinct()->pluck('category');

        return view('blog::shop.index', compact('posts', 'categories'));
    }

    public function show(string $slug)
    {
        $post    = BlogPost::published()->where('slug', $slug)->firstOrFail();
        $related = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->where('category', $post->category)
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('blog::shop.show', compact('post', 'related'));
    }
}
