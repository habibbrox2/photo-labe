<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CacheService;

class WarmCache extends Command
{
    protected $signature = 'cache:warm';
    protected $description = 'Warm up all application caches for better performance';

    public function handle()
    {
        $this->info('Warming up caches...');

        CacheService::warmUp();

        $this->info('✅ All caches warmed up successfully!');

        // Show cache stats
        $this->newLine();
        $this->info('Cache contents:');
        $this->line('  • Site settings');
        $this->line('  • Active services');
        $this->line('  • Portfolio projects');
        $this->line('  • Featured products');
        $this->line('  • Blog posts');
        $this->line('  • Testimonials');
        $this->line('  • Before/After items');

        return Command::SUCCESS;
    }
}
