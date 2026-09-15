<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            // Optional hero copy, so a CMS page can keep the designed page header
            // (eyebrow + lead sentence) instead of only its title.
            $table->string('eyebrow')->nullable()->after('title');
            $table->text('subtitle')->nullable()->after('eyebrow');

            // Binds a hand-written route (e.g. /about) to this page without freezing
            // its slug, which stays free for editors to change.
            $table->string('system_key')->nullable()->unique()->after('slug');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropUnique(['system_key']);
            $table->dropColumn(['eyebrow', 'subtitle', 'system_key']);
        });
    }
};
