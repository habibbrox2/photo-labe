<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Media Folders (must be created before media)
        Schema::create('media_folders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('parent_id')->nullable()->constrained('media_folders')->nullOnDelete();
            $table->string('slug');
            $table->timestamps();
        });

        // Reviews
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->morphs('reviewable'); // service, product, order
            $table->tinyInteger('rating')->unsigned();
            $table->text('comment')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });

        // Testimonials
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title')->nullable();
            $table->string('company')->nullable();
            $table->string('avatar')->nullable();
            $table->text('content');
            $table->tinyInteger('rating')->unsigned()->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // Media
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('folder_id')->nullable()->constrained('media_folders')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('original_name');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->string('alt_text')->nullable();
            $table->string('title')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Support Tickets
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('ticket_number')->unique();
            $table->string('subject');
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->string('status')->default('open');
            $table->timestamps();
            $table->softDeletes();
        });

        // Support Ticket Messages
        Schema::create('support_ticket_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('support_tickets')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('message');
            $table->json('attachments')->nullable();
            $table->boolean('is_staff')->default(false);
            $table->timestamps();
        });

        // Settings
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('group')->default('general');
            $table->string('key')->unique();
            $table->longText('value')->nullable();
            $table->string('type')->default('text');
            $table->timestamps();
        });

        // Coupons
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('type'); // fixed, percentage
            $table->decimal('value', 10, 2);
            $table->decimal('min_amount', 10, 2)->nullable();
            $table->decimal('max_uses', 10, 2)->nullable();
            $table->integer('used_count')->default(0);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Coupons Usage
        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('discount_amount', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon_usages');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('support_ticket_messages');
        Schema::dropIfExists('support_tickets');
        Schema::dropIfExists('media');
        Schema::dropIfExists('media_folders');
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('reviews');
    }
};
