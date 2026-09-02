<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BeforeAfterProject;
use App\Models\BeforeAfterCategory;
use Illuminate\Http\Request;

class BeforeAfterController extends Controller
{
    public function index(Request $request)
    {
        $query = BeforeAfterProject::with('category');

        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        $projects = $query->latest()->paginate(15)->withQueryString();

        return view('admin.before-after.index', compact('projects'));
    }

    public function create()
    {
        $categories = BeforeAfterCategory::active()->ordered()->get();

        return view('admin.before-after.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:before_after_categories,id',
            'title' => 'required|string|max:255',
            'before_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'after_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'description' => 'nullable|string|max:1000',
            'is_featured' => 'boolean',
            'status' => 'required|in:draft,published',
            'sort_order' => 'integer|min:0',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('before_image')) {
            $validated['before_image'] = $request->file('before_image')->store('before-after', 'public');
        }
        if ($request->hasFile('after_image')) {
            $validated['after_image'] = $request->file('after_image')->store('before-after', 'public');
        }

        BeforeAfterProject::create($validated);

        return redirect()->route('admin.before-after.index')->with('success', 'Before/After project created successfully.');
    }

    public function edit(BeforeAfterProject $project)
    {
        $categories = BeforeAfterCategory::active()->ordered()->get();

        return view('admin.before-after.edit', compact('project', 'categories'));
    }

    public function update(Request $request, BeforeAfterProject $project)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:before_after_categories,id',
            'title' => 'required|string|max:255',
            'before_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'after_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'description' => 'nullable|string|max:1000',
            'is_featured' => 'boolean',
            'status' => 'required|in:draft,published',
            'sort_order' => 'integer|min:0',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('before_image')) {
            $validated['before_image'] = $request->file('before_image')->store('before-after', 'public');
        }
        if ($request->hasFile('after_image')) {
            $validated['after_image'] = $request->file('after_image')->store('before-after', 'public');
        }

        $project->update($validated);

        return redirect()->route('admin.before-after.index')->with('success', 'Before/After project updated successfully.');
    }

    public function destroy(BeforeAfterProject $project)
    {
        $project->delete();

        return redirect()->route('admin.before-after.index')->with('success', 'Before/After project deleted successfully.');
    }
}
