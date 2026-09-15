<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'PhotoLabe', 'group' => 'general'],
            ['key' => 'site_email', 'value' => 'hello@photolabe.com', 'group' => 'general'],
            ['key' => 'support_email', 'value' => 'support@photolabe.com', 'group' => 'general'],
            ['key' => 'phone', 'value' => '+1 (555) 123-4567', 'group' => 'general'],
            ['key' => 'address', 'value' => '123 Creative Street, Design City, DC 10001', 'group' => 'general'],
            ['key' => 'currency', 'value' => 'BDT', 'group' => 'general'],
            ['key' => 'timezone', 'value' => 'UTC', 'group' => 'general'],
            ['key' => 'default_meta_title', 'value' => 'PhotoLabe - Professional Photo Editing & Creative Design Services', 'group' => 'seo'],
            ['key' => 'default_meta_description', 'value' => 'Transform your images into professional, market-ready visuals with expert photo editing and creative design services.', 'group' => 'seo'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
