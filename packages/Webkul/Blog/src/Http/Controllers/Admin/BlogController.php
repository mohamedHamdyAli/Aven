<?php

namespace Webkul\Blog\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Webkul\Blog\Models\BlogPost;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::orderByDesc('created_at')->paginate(20);

        return view('blog::admin.index', compact('posts'));
    }

    public function create()
    {
        return view('blog::admin.create', ['post' => null]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('blog', 'public');
        }

        $data['slug']         = BlogPost::generateSlug($data['title']);
        $data['author_id']    = auth()->guard('admin-guard')->id();
        $data['published_at'] = $data['status'] === 'published' ? now() : null;
        $data['tags']         = array_filter(array_map('trim', explode(',', $data['tags'] ?? '')));

        BlogPost::create($data);

        session()->flash('success', 'Blog post created.');

        return redirect()->route('admin.blog.index');
    }

    public function edit(int $id)
    {
        $post = BlogPost::findOrFail($id);

        return view('blog::admin.create', compact('post'));
    }

    public function update(Request $request, int $id)
    {
        $post = BlogPost::findOrFail($id);
        $data = $this->validated($request);

        if ($request->hasFile('featured_image')) {
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            $data['featured_image'] = $request->file('featured_image')->store('blog', 'public');
        }

        if ($data['status'] === 'published' && ! $post->published_at) {
            $data['published_at'] = now();
        }

        $data['tags'] = array_filter(array_map('trim', explode(',', $data['tags'] ?? '')));

        $post->update($data);

        session()->flash('success', 'Blog post updated.');

        return redirect()->route('admin.blog.index');
    }

    public function destroy(int $id)
    {
        $post = BlogPost::findOrFail($id);
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }
        $post->delete();

        return response()->json(['message' => 'Deleted.']);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title'            => 'required|string|max:200',
            'content'          => 'required|string',
            'excerpt'          => 'nullable|string|max:500',
            'featured_image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'category'         => 'nullable|string|max:100',
            'tags'             => 'nullable|string',
            'status'           => 'required|in:draft,published',
            'meta_title'       => 'nullable|string|max:200',
            'meta_description' => 'nullable|string|max:300',
        ]);
    }
}
