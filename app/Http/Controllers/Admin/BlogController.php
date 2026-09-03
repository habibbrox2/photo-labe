<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::with('category', 'author');

        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $posts = $query->latest()->paginate(15)->withQueryString();

        return view('admin.blog.index', compact('posts'));
    }

    public function create()
    {
        $categories = BlogCategory::active()->ordered()->get();
        $tags = BlogTag::orderBy('name')->get();

        return view('admin.blog.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:blog_categories,id',
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'is_featured' => 'boolean',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:blog_tags,id',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['author_id'] = auth()->id();
        $validated['is_featured'] = $request->boolean('is_featured');

        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('blog', 'public');
        }

        $tags = $validated['tags'] ?? [];
        unset($validated['tags']);

        $post = BlogPost::create($validated);
        $post->tags()->sync($tags);

        return redirect()->route('admin.blog.index')->with('success', 'Blog post created successfully.');
    }

    public function edit(BlogPost $blog)
    {
        $categories = BlogCategory::active()->ordered()->get();
        $tags = BlogTag::orderBy('name')->get();
        $blog->load('tags');

        return view('admin.blog.edit', ['post' => $blog, 'categories' => $categories, 'tags' => $tags]);
    }

    public function update(Request $request, BlogPost $blog)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:blog_categories,id',
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'is_featured' => 'boolean',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:blog_tags,id',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_featured'] = $request->boolean('is_featured');

        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = $blog->published_at ?? now();
        }

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('blog', 'public');
        }

        $tags = $validated['tags'] ?? [];
        unset($validated['tags']);

        $blog->update($validated);
        $blog->tags()->sync($tags);

        return redirect()->route('admin.blog.index')->with('success', 'Blog post updated successfully.');
    }

    public function destroy(BlogPost $blog)
    {
        $blog->delete();

        return redirect()->route('admin.blog.index')->with('success', 'Blog post deleted successfully.');
    }
}
