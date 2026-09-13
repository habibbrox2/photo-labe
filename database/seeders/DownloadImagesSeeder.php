<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DownloadImagesSeeder extends Seeder
{
    /**
     * Unsplash image URLs (free for commercial use, no attribution required)
     * These are direct image URLs from Unsplash
     */
    private array $images = [
        // Service images
        'services' => [
            'photo-retouching' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=800&q=80',
            'background-removal' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&q=80',
            'color-correction' => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=800&q=80',
            'graphic-design' => 'https://images.unsplash.com/photo-1558655146-9f40138edfeb?w=800&q=80',
            'jewelry-retouching' => 'https://images.unsplash.com/photo-1573408301185-9146fe634ad0?w=800&q=80',
            'clipping-path' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&q=80',
        ],

        // Portfolio images
        'portfolio' => [
            'ecommerce-product-shoot' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&q=80',
            'luxury-diamond-collection' => 'https://images.unsplash.com/photo-1573408301185-9146fe634ad0?w=800&q=80',
            'summer-fashion-campaign' => 'https://images.unsplash.com/photo-1469334031218-e382a71b716b?w=800&q=80',
            'luxury-apartment-listings' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&q=80',
            'destination-wedding-album' => 'https://images.unsplash.com/photo-1519741497674-611481863552?w=800&q=80',
            'brand-identity-design' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?w=800&q=80',
        ],

        // Before/After images
        'before_after' => [
            'portrait-retouching-before' => 'https://images.unsplash.com/photo-1531746020798-e6953c6e8e04?w=800&q=80',
            'portrait-retouching-after' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=800&q=80',
            'product-background-before' => 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?w=800&q=80',
            'product-background-after' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&q=80',
            'color-grading-before' => 'https://images.unsplash.com/photo-1502920917128-1aa500764cbd?w=800&q=80',
            'color-grading-after' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=800&q=80',
            'beauty-retouching-before' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=800&q=80',
            'beauty-retouching-after' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=800&q=80',
        ],

        // Product images
        'products' => [
            'cinematic-film-presets' => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=800&q=80',
            'portrait-perfection-presets' => 'https://images.unsplash.com/photo-1531746020798-e6953c6e8e04?w=800&q=80',
            'skin-retouching-actions' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=800&q=80',
            'cinematic-luts-pack' => 'https://images.unsplash.com/photo-1492691527719-9d1e07e534b4?w=800&q=80',
        ],

        // Testimonial avatars
        'avatars' => [
            'sarah-johnson' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=200&q=80',
            'michael-chen' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&q=80',
            'emily-rodriguez' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=200&q=80',
            'david-kim' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=200&q=80',
            'amanda-foster' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=200&q=80',
        ],

        // Hero image
        'hero' => [
            'main' => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=1200&q=80',
        ],
    ];

    public function run(): void
    {
        $this->command->info('🖼️  Starting image download from Unsplash...');

        foreach ($this->images as $category => $images) {
            $this->command->info("📥 Downloading {$category} images...");

            foreach ($images as $name => $url) {
                $this->downloadImage($category, $name, $url);
            }
        }

        $this->command->info('✅ All images downloaded successfully!');
    }

    private function downloadImage(string $category, string $name, string $url): void
    {
        try {
            $filename = Str::slug($name) . '.jpg';
            $path = "demo/{$category}/{$filename}";

            // Check if already downloaded
            if (Storage::disk('public')->exists($path)) {
                $this->command->info("  ⏭️  Skipping {$name} (already exists)");
                return;
            }

            $response = Http::timeout(30)->get($url);

            if ($response->successful()) {
                Storage::disk('public')->put($path, $response->body());
                $this->command->info("  ✅ Downloaded: {$name}");
            } else {
                $this->command->error("  ❌ Failed to download: {$name} (HTTP {$response->status()})");
            }
        } catch (\Exception $e) {
            $this->command->error("  ❌ Error downloading {$name}: {$e->getMessage()}");
        }
    }
}
