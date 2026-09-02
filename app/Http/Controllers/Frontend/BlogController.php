<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogCategory;

class BlogController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::active()->ordered()->withCount('posts')->get();
        $posts = BlogPost::active()->published()->recent()
            ->with('category', 'author')
            ->paginate(9);

        return view('frontend.blog.index', compact('categories', 'posts'));
    }

    public function show(string $slug)
    {
        $post = BlogPost::where('slug', $slug)
            ->active()
            ->published()
            ->with('category', 'author', 'tags')
            ->firstOrFail();

        // Increment views
        $post->increment('views_count');

        $relatedPosts = BlogPost::active()->published()
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->with('category')
            ->limit(3)
            ->get();

        return view('frontend.blog.show', compact('post', 'relatedPosts'));
    }
}
