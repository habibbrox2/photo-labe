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
        $baseUrl = config('app.url', 'https://photolabe.com');

        $urls = collect();

        // Homepage
        $urls->push([
            'loc' => $baseUrl,
            'priority' => '1.0',
            'changefreq' => 'daily',
            'images' => [asset('storage/demo/hero/main.jpg')],
        ]);

        // Static pages
        $staticPages = [
            ['loc' => '/services', 'priority' => '0.9', 'changefreq' => 'weekly', 'images' => []],
            ['loc' => '/portfolio', 'priority' => '0.9', 'changefreq' => 'weekly', 'images' => []],
            ['loc' => '/products', 'priority' => '0.9', 'changefreq' => 'weekly', 'images' => []],
            ['loc' => '/blog', 'priority' => '0.8', 'changefreq' => 'daily', 'images' => []],
            ['loc' => '/about', 'priority' => '0.7', 'changefreq' => 'monthly', 'images' => []],
            ['loc' => '/contact', 'priority' => '0.7', 'changefreq' => 'monthly', 'images' => []],
            ['loc' => '/faq', 'priority' => '0.6', 'changefreq' => 'monthly', 'images' => []],
            ['loc' => '/pricing', 'priority' => '0.6', 'changefreq' => 'monthly', 'images' => []],
            ['loc' => '/before-after', 'priority' => '0.7', 'changefreq' => 'weekly', 'images' => []],
            ['loc' => '/get-a-quote', 'priority' => '0.8', 'changefreq' => 'monthly', 'images' => []],
        ];

        foreach ($staticPages as $page) {
            $urls->push(array_merge($page, ['loc' => $baseUrl . $page['loc']]));
        }

        // Services
        Service::active()->orderBy('updated_at', 'desc')->chunk(50, function ($services) use ($urls, $baseUrl) {
            foreach ($services as $service) {
                $images = [];
                if ($service->featured_image) {
                    $images[] = $baseUrl . '/storage/' . $service->featured_image;
                }
                
                $urls->push([
                    'loc' => $baseUrl . '/services/' . $service->slug,
                    'lastmod' => $service->updated_at->format('Y-m-d'),
                    'priority' => '0.8',
                    'changefreq' => 'weekly',
                    'images' => $images,
                ]);
            }
        });

        // Portfolio
        PortfolioProject::active()->orderBy('updated_at', 'desc')->chunk(50, function ($projects) use ($urls, $baseUrl) {
            foreach ($projects as $project) {
                $images = [];
                if ($project->featured_image) {
                    $images[] = $baseUrl . '/storage/' . $project->featured_image;
                }
                
                $urls->push([
                    'loc' => $baseUrl . '/portfolio/' . $project->slug,
                    'lastmod' => $project->updated_at->format('Y-m-d'),
                    'priority' => '0.7',
                    'changefreq' => 'weekly',
                    'images' => $images,
                ]);
            }
        });

        // Products
        Product::active()->orderBy('updated_at', 'desc')->chunk(50, function ($products) use ($urls, $baseUrl) {
            foreach ($products as $product) {
                $images = [];
                if ($product->featured_image) {
                    $images[] = $baseUrl . '/storage/' . $product->featured_image;
                }
                
                $urls->push([
                    'loc' => $baseUrl . '/products/' . $product->slug,
                    'lastmod' => $product->updated_at->format('Y-m-d'),
                    'priority' => '0.8',
                    'changefreq' => 'weekly',
                    'images' => $images,
                ]);
            }
        });

        // Blog Posts
        BlogPost::active()->published()->orderBy('published_at', 'desc')->chunk(50, function ($posts) use ($urls, $baseUrl) {
            foreach ($posts as $post) {
                $images = [];
                if ($post->featured_image) {
                    $images[] = $baseUrl . '/storage/' . $post->featured_image;
                }
                
                $urls->push([
                    'loc' => $baseUrl . '/blog/' . $post->slug,
                    'lastmod' => $post->updated_at->format('Y-m-d'),
                    'priority' => '0.7',
                    'changefreq' => 'monthly',
                    'images' => $images,
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
                    'images' => [],
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
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
        $xml .= '        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";

        foreach ($urls as $url) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . e($url['loc']) . "</loc>\n";
            
            if (isset($url['lastmod'])) {
                $xml .= "    <lastmod>" . $url['lastmod'] . "</lastmod>\n";
            }
            
            $xml .= "    <changefreq>" . ($url['changefreq'] ?? 'monthly') . "</changefreq>\n";
            $xml .= "    <priority>" . ($url['priority'] ?? '0.5') . "</priority>\n";
            
            // Images
            if (!empty($url['images'])) {
                foreach ($url['images'] as $image) {
                    $xml .= "    <image:image>\n";
                    $xml .= "      <image:loc>" . e($image) . "</image:loc>\n";
                    $xml .= "    </image:image>\n";
                }
            }
            
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return $xml;
    }
}
