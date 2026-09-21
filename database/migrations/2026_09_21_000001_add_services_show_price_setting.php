<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Seed the services_show_price setting (public service price visibility),
     * defaulted to "1" so existing sites keep showing prices.
     */
    public function up(): void
    {
        DB::table('settings')->updateOrInsert(
            ['key' => 'services_show_price'],
            [
                'group' => 'general',
                'value' => '1',
                'type' => 'boolean',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    public function down(): void
    {
        DB::table('settings')->where('key', 'services_show_price')->delete();
    }
};
