<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // First download images from the internet
        $this->call([
            DownloadImagesSeeder::class,
        ]);

        // Then seed the database with the downloaded images
        $this->call([
            UserSeeder::class,
            ServiceSeeder::class,
            PortfolioSeeder::class,
            BeforeAfterSeeder::class,
            ProductSeeder::class,
            HeroSlideSeeder::class,
            TestimonialSeeder::class,
            SettingSeeder::class,
            PageSeeder::class,
        ]);
    }
}
