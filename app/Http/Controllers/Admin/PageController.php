<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $query = Page::query();

        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $pages = $query->latest()->paginate(15)->withQueryString();

        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $request->merge(['slug' => Str::slug($request->input('slug') ?: $request->input('title'))]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', Rule::unique('pages', 'slug')],
            'eyebrow' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'template' => 'nullable|string|max:100',
            'status' => 'required|in:draft,published',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('pages', 'public');
        }

        Page::create($validated);

        return redirect()->route('admin.pages.index')->with('success', 'Page created successfully.');
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        // The slug is edited explicitly, never re-derived from the title: silently
        // regenerating it would move a published page to a new URL on a typo fix.
        $request->merge(['slug' => Str::slug($request->input('slug') ?: $page->slug)]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', Rule::unique('pages', 'slug')->ignore($page->id)],
            'eyebrow' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'template' => 'nullable|string|max:100',
            'status' => 'required|in:draft,published',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('pages', 'public');
        }

        $page->update($validated);

        return redirect()->route('admin.pages.index')->with('success', 'Page updated successfully.');
    }

    public function destroy(Page $page)
    {
        // Deleting a page a hand-written route depends on would silently drop the
        // site back to its built-in copy, so it is blocked rather than allowed.
        if ($page->isSystemPage()) {
            return back()->with('error', 'This page powers '.$page->publicUrl().' and cannot be deleted. Unpublish it instead.');
        }

        $page->delete();

        return redirect()->route('admin.pages.index')->with('success', 'Page deleted successfully.');
    }
}
