<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Portfolio Categories
        Schema::create('portfolio_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Portfolio Projects
        Schema::create('portfolio_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('portfolio_categories')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('client')->nullable();
            $table->text('description')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('url')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('status')->default('draft');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // Portfolio Images (Gallery)
        Schema::create('portfolio_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('portfolio_projects')->cascadeOnDelete();
            $table->string('image');
            $table->string('alt')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Portfolio Tags
        Schema::create('portfolio_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // Portfolio Project Tag Pivot
        Schema::create('portfolio_project_tag', function (Blueprint $table) {
            $table->foreignId('project_id')->constrained('portfolio_projects')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('portfolio_tags')->cascadeOnDelete();
            $table->primary(['project_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_project_tag');
        Schema::dropIfExists('portfolio_tags');
        Schema::dropIfExists('portfolio_images');
        Schema::dropIfExists('portfolio_projects');
        Schema::dropIfExists('portfolio_categories');
    }
};
