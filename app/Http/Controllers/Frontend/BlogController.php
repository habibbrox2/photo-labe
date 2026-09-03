<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Services\SeoService;

class BlogController extends Controller
{
    public function index(SeoService $seo)
    {
        $categories = BlogCategory::active()->ordered()->withCount('posts')->get();
        $posts = BlogPost::active()->published()->recent()
            ->with('category', 'author')
            ->paginate(9);

        $seoData = $seo->getMeta([
            'title' => 'Blog - Photo Editing Tips & Tutorials',
            'description' => 'Expert tips, tutorials, and industry news about photo editing, retouching, color correction, and creative design.',
            'keywords' => [
                'photo editing blog',
                'retouching tutorials',
                'color correction tips',
                'photography tips',
                'photo editing guide',
                'e-commerce photography',
                'product photography tips',
            ],
            'breadcrumb' => [
                ['name' => 'Home', 'url' => '/'],
                ['name' => 'Blog', 'url' => '/blog'],
            ],
        ]);

        return view('frontend.blog.index', compact('categories', 'posts', 'seoData'));
    }

    public function show(string $slug, SeoService $seo)
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

        $seoData = $seo->getBlogMeta($post);

        return view('frontend.blog.show', compact('post', 'relatedPosts', 'seoData'));
    }
}
