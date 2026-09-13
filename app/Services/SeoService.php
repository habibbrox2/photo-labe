<?php

namespace App\Services;

use App\Models\Service;
use App\Models\PortfolioProject;
use App\Models\Product;
use Illuminate\Support\Collection;

class SeoService
{
    protected string $appName;
    protected string $appUrl;

    public function __construct()
    {
        $this->appName = config('app.name', 'PhotoLabe');
        $this->appUrl = config('app.url', 'https://photolabe.com');
    }

    /**
     * Get meta data for a page
     */
    public function getMeta(array $data): array
    {
        return [
            'title' => $this->getTitle($data['title'] ?? null),
            'description' => $this->getDescription($data['description'] ?? null),
            'image' => $this->getImage($data['image'] ?? null),
            'url' => $data['url'] ?? url()->current(),
            'type' => $data['type'] ?? 'website',
            'keywords' => $data['keywords'] ?? $this->getDefaultKeywords(),
            'schema' => $data['schema'] ?? null,
            'breadcrumb' => $data['breadcrumb'] ?? null,
            'published_time' => $data['published_time'] ?? null,
            'modified_time' => $data['modified_time'] ?? null,
            'author' => $data['author'] ?? null,
        ];
    }

    /**
     * Get service page meta
     */
    public function getServiceMeta(Service $service): array
    {
        return $this->getMeta([
            'title' => $service->seo_title ?: $service->title,
            'description' => $service->seo_description ?: $service->short_description,
            'image' => $service->featured_image ? asset('storage/' . $service->featured_image) : null,
            'type' => 'website',
            'keywords' => $this->getServiceKeywords($service),
            'schema' => $this->getServiceSchema($service),
            'breadcrumb' => $this->getServiceBreadcrumb($service),
        ]);
    }

    /**
     * Get portfolio page meta
     */
    public function getPortfolioMeta(PortfolioProject $project): array
    {
        return $this->getMeta([
            'title' => $project->seo_title ?: $project->title,
            'description' => $project->seo_description ?: $project->description,
            'image' => $project->featured_image ? asset('storage/' . $project->featured_image) : null,
            'type' => 'article',
            'keywords' => $this->getPortfolioKeywords($project),
            'schema' => $this->getPortfolioSchema($project),
            'breadcrumb' => $this->getPortfolioBreadcrumb($project),
            'author' => $project->client,
        ]);
    }

    /**
     * Get product page meta
     */
    public function getProductMeta(Product $product): array
    {
        return $this->getMeta([
            'title' => $product->seo_title ?: $product->title,
            'description' => $product->seo_description ?: $product->short_description,
            'image' => $product->featured_image ? asset('storage/' . $product->featured_image) : null,
            'type' => 'website',
            'keywords' => $this->getProductKeywords($product),
            'schema' => $this->getProductSchema($product),
            'breadcrumb' => $this->getProductBreadcrumb($product),
        ]);
    }

    /**
     * Get homepage schema
     */
    public function getHomepageSchema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => $this->appName,
            'description' => 'Professional photo editing and creative design services.',
            'url' => $this->appUrl,
            'mainEntity' => [
                '@type' => 'Organization',
                'name' => $this->appName,
                'url' => $this->appUrl,
                'logo' => asset('storage/demo/hero/main.jpg'),
                'description' => 'Professional photo editing and creative design services.',
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => '123 Creative Street',
                    'addressLocality' => 'Design City',
                    'addressRegion' => 'DC',
                    'postalCode' => '10001',
                    'addressCountry' => 'US',
                ],
                'contactPoint' => [
                    '@type' => 'ContactPoint',
                    'telephone' => '+1-555-123-4567',
                    'contactType' => 'customer service',
                ],
            ],
        ];
    }

    /**
     * Get FAQ schema
     */
    public function getFaqSchema(array $faqs): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => array_map(function ($faq) {
                return [
                    '@type' => 'Question',
                    'name' => $faq['question'],
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $faq['answer'],
                    ],
                ];
            }, $faqs),
        ];
    }

    /**
     * Get local business schema
     */
    public function getLocalBusinessSchema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => $this->appName,
            'image' => asset('storage/demo/hero/main.jpg'),
            'url' => $this->appUrl,
            'telephone' => '+1-555-123-4567',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => '123 Creative Street',
                'addressLocality' => 'Design City',
                'addressRegion' => 'DC',
                'postalCode' => '10001',
                'addressCountry' => 'US',
            ],
            'geo' => [
                '@type' => 'GeoCoordinates',
                'latitude' => 38.9072,
                'longitude' => -77.0369,
            ],
            'openingHoursSpecification' => [
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                'opens' => '09:00',
                'closes' => '18:00',
            ],
            'priceRange' => '$$',
            'aggregateRating' => [
                '@type' => 'AggregateRating',
                'ratingValue' => '4.9',
                'reviewCount' => '127',
            ],
        ];
    }

    /**
     * Get service schema
     */
    protected function getServiceSchema(Service $service): array
    {
        return [
            '@type' => 'Service',
            'name' => $service->title,
            'description' => $service->short_description,
            'url' => route('services.show', $service->slug),
            'image' => $service->featured_image ? asset('storage/' . $service->featured_image) : null,
            'provider' => [
                '@type' => 'Organization',
                'name' => $this->appName,
            ],
            'offers' => $service->starting_price ? [
                '@type' => 'Offer',
                'price' => $service->starting_price,
                'priceCurrency' => 'USD',
                'availability' => 'https://schema.org/InStock',
            ] : null,
        ];
    }

    /**
     * Get portfolio schema
     */
    protected function getPortfolioSchema(PortfolioProject $project): array
    {
        return [
            '@type' => 'CreativeWork',
            'name' => $project->title,
            'description' => $project->description,
            'url' => route('portfolio.show', $project->slug),
            'image' => $project->featured_image ? asset('storage/' . $project->featured_image) : null,
            'author' => $project->client ? [
                '@type' => 'Organization',
                'name' => $project->client,
            ] : null,
            'dateCreated' => $project->created_at?->toIso8601String(),
            'dateModified' => $project->updated_at?->toIso8601String(),
        ];
    }

    /**
     * Get product schema
     */
    protected function getProductSchema(Product $product): array
    {
        return [
            '@type' => 'Product',
            'name' => $product->title,
            'description' => $product->short_description,
            'url' => route('products.show', $product->slug),
            'image' => $product->featured_image ? asset('storage/' . $product->featured_image) : null,
            'brand' => [
                '@type' => 'Brand',
                'name' => $this->appName,
            ],
            'offers' => [
                '@type' => 'Offer',
                'price' => $product->price,
                'priceCurrency' => 'USD',
                'availability' => 'https://schema.org/InStock',
                'seller' => [
                    '@type' => 'Organization',
                    'name' => $this->appName,
                ],
            ],
        ];
    }

    /**
     * Get breadcrumbs
     */
    protected function getServiceBreadcrumb(Service $service): array
    {
        return [
            ['name' => 'Home', 'url' => '/'],
            ['name' => 'Services', 'url' => '/services'],
            ['name' => $service->title, 'url' => '/services/' . $service->slug],
        ];
    }

    protected function getPortfolioBreadcrumb(PortfolioProject $project): array
    {
        return [
            ['name' => 'Home', 'url' => '/'],
            ['name' => 'Portfolio', 'url' => '/portfolio'],
            ['name' => $project->title, 'url' => '/portfolio/' . $project->slug],
        ];
    }

    protected function getProductBreadcrumb(Product $product): array
    {
        return [
            ['name' => 'Home', 'url' => '/'],
            ['name' => 'Products', 'url' => '/products'],
            ['name' => $product->title, 'url' => '/products/' . $product->slug],
        ];
    }

    /**
     * Get keywords
     */
    protected function getDefaultKeywords(): array
    {
        return [
            'photo editing',
            'photo retouching',
            'background removal',
            'color correction',
            'clipping path',
            'jewelry retouching',
            'product photography',
            'creative design',
            'professional photo editing',
            'image editing services',
        ];
    }

    protected function getServiceKeywords(Service $service): array
    {
        $base = $this->getDefaultKeywords();
        $serviceKeywords = [
            strtolower($service->title),
            strtolower($service->category->name ?? ''),
            'photo editing service',
            'professional editing',
        ];
        return array_unique(array_merge($base, array_filter($serviceKeywords)));
    }

    protected function getPortfolioKeywords(PortfolioProject $project): array
    {
        return [
            strtolower($project->title),
            strtolower($project->category->name ?? ''),
            'portfolio',
            'photo editing examples',
            'before and after',
        ];
    }

    protected function getProductKeywords(Product $product): array
    {
        return [
            strtolower($product->title),
            strtolower($product->category->name ?? ''),
            'digital product',
            'photo editing tools',
            'presets',
            'actions',
        ];
    }

    /**
     * Helper methods
     */
    protected function getTitle(?string $title): string
    {
        return $title ? $title . ' - ' . $this->appName : $this->appName . ' - Professional Photo Editing & Creative Design';
    }

    protected function getDescription(?string $description): string
    {
        return $description ?? $this->appName . ' - Professional photo editing and creative design services. Transform your images into stunning visuals.';
    }

    protected function getImage(?string $image): string
    {
        if (!$image) {
            return asset('storage/demo/hero/main.jpg');
        }
        
        return str_starts_with($image, 'http') ? $image : asset('storage/' . $image);
    }
}
