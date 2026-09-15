<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        // firstOrCreate, never updateOrCreate: re-running the seeder must not wipe
        // copy an editor has since changed in the admin.
        Page::firstOrCreate(
            ['system_key' => Page::SYSTEM_ABOUT],
            [
                'title' => 'About PhotoLabe',
                'slug' => 'about',
                'eyebrow' => 'Our Studio',
                'subtitle' => 'A professional photo editing and creative design studio helping businesses look their best.',
                'content' => <<<'HTML'
                <h2>Our Story</h2>
                <p>PhotoLabe is a professional photo editing and creative design studio dedicated to helping businesses and individuals transform their visual content into stunning, market-ready assets.</p>
                <h2>Our Mission</h2>
                <p>We combine technical expertise with creative vision to deliver exceptional results that exceed our clients' expectations.</p>
                <h2>Our Team</h2>
                <p>Our team consists of experienced designers, photographers, and retouching specialists with years of industry experience.</p>
                HTML,
                'template' => 'default',
                'status' => 'published',
                'seo_title' => 'About Us - PhotoLabe',
                'seo_description' => 'PhotoLabe is a professional photo editing and creative design studio helping businesses transform their visual content into market-ready assets.',
            ]
        );
    }
}
