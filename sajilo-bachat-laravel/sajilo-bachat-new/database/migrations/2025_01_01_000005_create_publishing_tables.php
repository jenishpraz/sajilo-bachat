<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->string('type', 20)->default('general');   // general | auction
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('slug')->unique();
            $table->string('title_en');
            $table->string('title_ne')->nullable();
            $table->text('body_en')->nullable();
            $table->text('body_ne')->nullable();
            $table->string('attachment')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_important')->default(false);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
            $table->index(['type', 'is_published', 'published_at']);
        });

        Schema::create('gallery_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('title_en')->nullable();
            $table->string('title_ne')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_ne')->nullable();
            $table->string('image');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['is_active', 'sort_order']);
        });

        Schema::create('gallery_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('title_en');
            $table->string('title_ne')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_ne')->nullable();
            $table->string('url', 500);
            $table->string('thumbnail')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['is_active', 'sort_order']);
        });

        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('slug')->unique();
            $table->string('title_en');
            $table->string('title_ne')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_ne')->nullable();
            $table->string('location_en')->nullable();
            $table->string('location_ne')->nullable();
            $table->date('event_date');
            $table->date('end_date')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
            $table->index(['is_published', 'event_date']);
        });

        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();
            $table->string('title_en');
            $table->string('title_ne')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_ne')->nullable();
            $table->string('type', 20)->default('event');
            $table->date('event_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['is_active', 'event_date']);
        });

        Schema::create('downloads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('title_en');
            $table->string('title_ne')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_ne')->nullable();
            $table->string('file');
            $table->unsignedInteger('download_count')->default(0);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('downloads');
        Schema::dropIfExists('calendar_events');
        Schema::dropIfExists('events');
        Schema::dropIfExists('gallery_videos');
        Schema::dropIfExists('gallery_photos');
        Schema::dropIfExists('notices');
    }
};
