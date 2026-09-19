<?php

namespace Database\Seeders;

use App\Models\HeroSlide;
use Illuminate\Database\Seeder;

class HeroSlideSeeder extends Seeder
{
    public function run(): void
    {
        $slides = [
            [
                'image' => 'demo/hero/hero-jewelry.jpg',
                'headline' => 'Jewelry that shines as bright as your brand.',
                'caption_label' => 'Jewelry Retouching',
                'caption_text' => 'Luxury Diamond Collection — GemHouse',
                'link_url' => '/portfolio',
                'sort_order' => 1,
            ],
            [
                'image' => 'demo/hero/hero-headphone.jpg',
                'headline' => 'Pixel-perfect product shots that sell.',
                'caption_label' => 'Product Photography',
                'caption_text' => 'E-commerce Headphone Shoot — AudioMax',
                'link_url' => '/portfolio',
                'sort_order' => 2,
            ],
            [
                'image' => 'demo/hero/hero-shoes.jpg',
                'headline' => 'Footwear imagery built to convert.',
                'caption_label' => 'Footwear',
                'caption_text' => 'Sneaker Campaign — StepUp',
                'link_url' => '/portfolio',
                'sort_order' => 3,
            ],
            [
                'image' => 'demo/hero/hero-sunglass.jpg',
                'headline' => 'Eyewear edits with flawless detail.',
                'caption_label' => 'Accessories',
                'caption_text' => 'Sunglass Lookbook — ShadeCo',
                'link_url' => '/portfolio',
                'sort_order' => 4,
            ],
            [
                'image' => 'demo/hero/hero-model.jpg',
                'headline' => 'Fashion retouching that keeps it real.',
                'caption_label' => 'Fashion',
                'caption_text' => 'Summer Fashion Campaign — StyleBrand',
                'link_url' => '/portfolio',
                'sort_order' => 5,
            ],
            [
                'image' => 'demo/hero/hero-extra-1.jpg',
                'headline' => 'Every frame, perfected.',
                'caption_label' => 'Studio Work',
                'caption_text' => 'Editorial Retouching — Studio Session',
                'link_url' => '/portfolio',
                'sort_order' => 6,
            ],
            [
                'image' => 'demo/hero/hero-extra-2.jpg',
                'headline' => 'Color grading that sets the mood.',
                'caption_label' => 'Color Grading',
                'caption_text' => 'Cinematic Look — Creative Direction',
                'link_url' => '/portfolio',
                'sort_order' => 7,
            ],
            [
                'image' => 'demo/hero/hero-extra-3.jpg',
                'headline' => 'Details your customers can feel.',
                'caption_label' => 'Detail Retouching',
                'caption_text' => 'Macro Product Edit — Precision Work',
                'link_url' => '/portfolio',
                'sort_order' => 8,
            ],
        ];

        foreach ($slides as $slide) {
            HeroSlide::updateOrCreate(
                ['image' => $slide['image']],
                $slide + ['is_active' => true]
            );
        }
    }
}
