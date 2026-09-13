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
                'image' => 'demo/portfolio/ecommerce-product-shoot.jpg',
                'caption_label' => 'Product Photography',
                'caption_text' => 'E-commerce Product Shoot — FashionCo',
                'link_url' => '/portfolio/ecommerce-product-shoot',
                'sort_order' => 1,
            ],
            [
                'image' => 'demo/portfolio/luxury-diamond-collection.jpg',
                'headline' => 'Jewelry that shines as bright as your brand.',
                'caption_label' => 'Jewelry',
                'caption_text' => 'Luxury Diamond Collection — GemHouse',
                'link_url' => '/portfolio/luxury-diamond-collection',
                'sort_order' => 2,
            ],
            [
                'image' => 'demo/portfolio/summer-fashion-campaign.jpg',
                'caption_label' => 'Fashion',
                'caption_text' => 'Summer Fashion Campaign — StyleBrand',
                'link_url' => '/portfolio/summer-fashion-campaign',
                'sort_order' => 3,
            ],
            [
                'image' => 'demo/portfolio/luxury-apartment-listings.jpg',
                'caption_label' => 'Real Estate',
                'caption_text' => 'Luxury Apartment Listings — PrimeRealty',
                'link_url' => '/portfolio/luxury-apartment-listings',
                'sort_order' => 4,
            ],
            [
                'image' => 'demo/portfolio/destination-wedding-album.jpg',
                'caption_label' => 'Wedding',
                'caption_text' => 'Destination Wedding Album — Sarah & Mike',
                'link_url' => '/portfolio/destination-wedding-album',
                'sort_order' => 5,
            ],
            [
                'image' => 'demo/portfolio/brand-identity-design.jpg',
                'caption_label' => 'Graphic Design',
                'caption_text' => 'Brand Identity Design — TechStartup',
                'link_url' => '/portfolio/brand-identity-design',
                'sort_order' => 6,
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
