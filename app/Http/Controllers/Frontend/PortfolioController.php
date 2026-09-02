<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\PortfolioProject;
use App\Models\PortfolioCategory;

class PortfolioController extends Controller
{
    public function index()
    {
        $categories = PortfolioCategory::active()->ordered()->withCount('projects')->get();
        $projects = PortfolioProject::active()->ordered()->with('category', 'images')->paginate(12);

        return view('frontend.portfolio.index', compact('categories', 'projects'));
    }

    public function show(string $slug)
    {
        $project = PortfolioProject::where('slug', $slug)
            ->active()
            ->with('category', 'images', 'tags')
            ->firstOrFail();

        $relatedProjects = PortfolioProject::active()
            ->where('category_id', $project->category_id)
            ->where('id', '!=', $project->id)
            ->with('category')
            ->limit(3)
            ->get();

        return view('frontend.portfolio.show', compact('project', 'relatedProjects'));
    }
}
