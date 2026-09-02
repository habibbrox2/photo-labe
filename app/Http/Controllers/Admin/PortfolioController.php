<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PortfolioProject;
use App\Models\PortfolioCategory;
use App\Models\PortfolioTag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PortfolioController extends Controller
{
    public function index(Request $request)
    {
        $query = PortfolioProject::with('category');

        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        $projects = $query->latest()->paginate(15)->withQueryString();

        return view('admin.portfolio.index', compact('projects'));
    }

    public function create()
    {
        $categories = PortfolioCategory::active()->ordered()->get();
        $tags = PortfolioTag::orderBy('name')->get();

        return view('admin.portfolio.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:portfolio_categories,id',
            'title' => 'required|string|max:255',
            'client' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'url' => 'nullable|url|max:500',
            'is_featured' => 'boolean',
            'status' => 'required|in:draft,published',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery_images' => 'nullable|array|max:20',
            'gallery_images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:portfolio_tags,id',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('portfolio', 'public');
        }

        $tags = $validated['tags'] ?? [];
        $galleryImages = $validated['gallery_images'] ?? [];
        unset($validated['tags'], $validated['gallery_images']);

        $project = PortfolioProject::create($validated);
        $project->tags()->sync($tags);

        // Handle gallery images
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $index => $file) {
                $path = $file->store('portfolio/gallery', 'public');
                $project->images()->create([
                    'image' => $path,
                    'alt' => $project->title . ' gallery ' . ($index + 1),
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('admin.portfolio.index')->with('success', 'Portfolio project created successfully.');
    }

    public function edit(PortfolioProject $project)
    {
        $categories = PortfolioCategory::active()->ordered()->get();
        $tags = PortfolioTag::orderBy('name')->get();
        $project->load('tags');

        return view('admin.portfolio.edit', compact('project', 'categories', 'tags'));
    }

    public function update(Request $request, PortfolioProject $project)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:portfolio_categories,id',
            'title' => 'required|string|max:255',
            'client' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'url' => 'nullable|url|max:500',
            'is_featured' => 'boolean',
            'status' => 'required|in:draft,published',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery_images' => 'nullable|array|max:20',
            'gallery_images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:portfolio_tags,id',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('portfolio', 'public');
        }

        $tags = $validated['tags'] ?? [];
        unset($validated['tags'], $validated['gallery_images']);

        $project->update($validated);
        $project->tags()->sync($tags);

        // Handle new gallery images
        if ($request->hasFile('gallery_images')) {
            $existingCount = $project->images()->count();
            foreach ($request->file('gallery_images') as $index => $file) {
                $path = $file->store('portfolio/gallery', 'public');
                $project->images()->create([
                    'image' => $path,
                    'alt' => $project->title . ' gallery ' . ($existingCount + $index + 1),
                    'sort_order' => $existingCount + $index,
                ]);
            }
        }

        return redirect()->route('admin.portfolio.index')->with('success', 'Portfolio project updated successfully.');
    }

    public function destroy(PortfolioProject $project)
    {
        $project->delete();

        return redirect()->route('admin.portfolio.index')->with('success', 'Portfolio project deleted successfully.');
    }
}
