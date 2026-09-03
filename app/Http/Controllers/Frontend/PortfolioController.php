<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\PortfolioProject;
use App\Models\PortfolioCategory;
use App\Services\SeoService;

class PortfolioController extends Controller
{
    public function index(SeoService $seo)
    {
        $categories = PortfolioCategory::active()->ordered()->withCount('projects')->get();
        $projects = PortfolioProject::active()->ordered()->with('category', 'images')->paginate(12);

        $seoData = $seo->getMeta([
            'title' => 'Portfolio - Our Recent Work',
            'description' => 'Browse our portfolio of photo editing, retouching, and creative design projects. See examples of our work across various industries.',
            'keywords' => [
                'photo editing portfolio',
                'retouching examples',
                'before and after',
                'product photography',
                'jewelry retouching',
                'fashion photography',
                'wedding photo editing',
                'real estate photo enhancement',
            ],
            'breadcrumb' => [
                ['name' => 'Home', 'url' => '/'],
                ['name' => 'Portfolio', 'url' => '/portfolio'],
            ],
        ]);

        return view('frontend.portfolio.index', compact('categories', 'projects', 'seoData'));
    }

    public function show(string $slug, SeoService $seo)
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

        $seoData = $seo->getPortfolioMeta($project);

        return view('frontend.portfolio.show', compact('project', 'relatedProjects', 'seoData'));
    }
}
