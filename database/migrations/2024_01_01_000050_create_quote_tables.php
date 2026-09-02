<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('phone', 30)->nullable();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('quantity')->default(1);
            $table->date('deadline')->nullable();
            $table->text('requirements')->nullable();
            $table->decimal('quoted_price', 10, 2)->nullable();
            $table->text('admin_notes')->nullable();
            $table->string('status')->default('pending');
            $table->string('priority')->default('normal');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('quote_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('quote_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->constrained()->cascadeOnDelete();
            $table->string('original_name');
            $table->string('stored_name');
            $table->string('file_path');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quote_files');
        Schema::dropIfExists('quote_items');
        Schema::dropIfExists('quotes');
    }
};
