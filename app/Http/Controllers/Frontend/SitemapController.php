<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\PortfolioProject;
use App\Models\Product;
use App\Models\BlogPost;
use App\Models\Page;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $baseUrl = config('app.url', 'https://piclab.com');

        $urls = collect();

        // Homepage
        $urls->push(['loc' => $baseUrl, 'priority' => '1.0', 'changefreq' => 'daily']);

        // Static pages
        $staticPages = [
            ['loc' => '/services', 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['loc' => '/portfolio', 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['loc' => '/products', 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['loc' => '/blog', 'priority' => '0.8', 'changefreq' => 'daily'],
            ['loc' => '/about', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['loc' => '/contact', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['loc' => '/faq', 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['loc' => '/pricing', 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['loc' => '/before-after', 'priority' => '0.7', 'changefreq' => 'weekly'],
            ['loc' => '/get-a-quote', 'priority' => '0.8', 'changefreq' => 'monthly'],
        ];

        foreach ($staticPages as $page) {
            $urls->push(array_merge($page, ['loc' => $baseUrl . $page['loc']]));
        }

        // Services
        Service::active()->orderBy('updated_at', 'desc')->chunk(50, function ($services) use ($urls, $baseUrl) {
            foreach ($services as $service) {
                $urls->push([
                    'loc' => $baseUrl . '/services/' . $service->slug,
                    'lastmod' => $service->updated_at->format('Y-m-d'),
                    'priority' => '0.8',
                    'changefreq' => 'weekly',
                ]);
            }
        });

        // Portfolio
        PortfolioProject::active()->orderBy('updated_at', 'desc')->chunk(50, function ($projects) use ($urls, $baseUrl) {
            foreach ($projects as $project) {
                $urls->push([
                    'loc' => $baseUrl . '/portfolio/' . $project->slug,
                    'lastmod' => $project->updated_at->format('Y-m-d'),
                    'priority' => '0.7',
                    'changefreq' => 'weekly',
                ]);
            }
        });

        // Products
        Product::active()->orderBy('updated_at', 'desc')->chunk(50, function ($products) use ($urls, $baseUrl) {
            foreach ($products as $product) {
                $urls->push([
                    'loc' => $baseUrl . '/products/' . $product->slug,
                    'lastmod' => $product->updated_at->format('Y-m-d'),
                    'priority' => '0.8',
                    'changefreq' => 'weekly',
                ]);
            }
        });

        // Blog Posts
        BlogPost::active()->published()->orderBy('published_at', 'desc')->chunk(50, function ($posts) use ($urls, $baseUrl) {
            foreach ($posts as $post) {
                $urls->push([
                    'loc' => $baseUrl . '/blog/' . $post->slug,
                    'lastmod' => $post->updated_at->format('Y-m-d'),
                    'priority' => '0.7',
                    'changefreq' => 'monthly',
                ]);
            }
        });

        // Dynamic Pages
        Page::where('status', 'published')->orderBy('updated_at', 'desc')->chunk(50, function ($pages) use ($urls, $baseUrl) {
            foreach ($pages as $page) {
                $urls->push([
                    'loc' => $baseUrl . '/page/' . $page->slug,
                    'lastmod' => $page->updated_at->format('Y-m-d'),
                    'priority' => '0.6',
                    'changefreq' => 'monthly',
                ]);
            }
        });

        $xml = $this->buildXml($urls);

        return response($xml, 200)
            ->header('Content-Type', 'application/xml')
            ->header('Cache-Control', 'public, max-age=3600');
    }

    protected function buildXml($urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $url) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . e($url['loc']) . "</loc>\n";
            if (isset($url['lastmod'])) {
                $xml .= "    <lastmod>" . $url['lastmod'] . "</lastmod>\n";
            }
            $xml .= "    <changefreq>" . ($url['changefreq'] ?? 'monthly') . "</changefreq>\n";
            $xml .= "    <priority>" . ($url['priority'] ?? '0.5') . "</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return $xml;
    }
}
