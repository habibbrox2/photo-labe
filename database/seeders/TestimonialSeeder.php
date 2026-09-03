<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            ['name' => 'Sarah Johnson', 'title' => 'Photographer', 'company' => 'Sarah J Photography', 'content' => 'Outstanding quality and incredibly fast turnaround. They have been handling all my retouching needs for over a year now.', 'rating' => 5, 'is_featured' => true, 'sort_order' => 1, 'avatar' => 'demo/avatars/sarah-johnson.jpg'],
            ['name' => 'Michael Chen', 'title' => 'E-commerce Manager', 'company' => 'FashionCo', 'content' => 'The background removal service is flawless. Our product images look professional and consistent across our entire catalog.', 'rating' => 5, 'is_featured' => true, 'sort_order' => 2, 'avatar' => 'demo/avatars/michael-chen.jpg'],
            ['name' => 'Emily Rodriguez', 'title' => 'Creative Director', 'company' => 'Brand Studio', 'content' => 'Their attention to detail is remarkable. Every project is delivered on time with exceptional quality.', 'rating' => 5, 'is_featured' => true, 'sort_order' => 3, 'avatar' => 'demo/avatars/emily-rodriguez.jpg'],
            ['name' => 'David Kim', 'title' => 'Wedding Photographer', 'company' => 'DK Photography', 'content' => 'They handle all my wedding photo editing. The consistency and quality across hundreds of images is impressive.', 'rating' => 5, 'is_featured' => true, 'sort_order' => 4, 'avatar' => 'demo/avatars/david-kim.jpg'],
            ['name' => 'Amanda Foster', 'title' => 'Real Estate Agent', 'company' => 'Prime Realty', 'content' => 'My listing photos have never looked better. Their real estate photo enhancement service is worth every penny.', 'rating' => 5, 'is_featured' => true, 'sort_order' => 5, 'avatar' => 'demo/avatars/amanda-foster.jpg'],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}
