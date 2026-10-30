<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title_en');
            $table->string('title_ne')->nullable();
            $table->string('subtitle_en', 500)->nullable();
            $table->string('subtitle_ne', 500)->nullable();
            $table->string('image')->nullable();
            $table->string('button_text_en', 60)->nullable();
            $table->string('button_text_ne', 60)->nullable();
            $table->string('button_url')->nullable();
            $table->string('button2_text_en', 60)->nullable();
            $table->string('button2_text_ne', 60)->nullable();
            $table->string('button2_url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['is_active', 'sort_order']);
        });

        Schema::create('site_contents', function (Blueprint $table) {
            $table->id();
            $table->string('key', 60)->unique();
            $table->string('title_en')->nullable();
            $table->string('title_ne')->nullable();
            $table->text('body_en')->nullable();
            $table->text('body_ne')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        // Objectives, core values and institutional achievements.
        Schema::create('list_items', function (Blueprint $table) {
            $table->id();
            $table->string('type', 30);
            $table->string('title_en');
            $table->string('title_ne')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_ne')->nullable();
            $table->string('icon', 40)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['type', 'is_active', 'sort_order']);
        });

        Schema::create('statistics', function (Blueprint $table) {
            $table->id();
            $table->string('label_en');
            $table->string('label_ne')->nullable();
            $table->string('value', 40);
            $table->string('prefix', 20)->nullable();
            $table->string('suffix', 20)->nullable();
            $table->string('icon', 40)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_draft')->default(false);
            $table->timestamps();
            $table->index(['is_active', 'sort_order']);
        });

        Schema::create('social_links', function (Blueprint $table) {
            $table->id();
            $table->string('platform', 30);
            $table->string('url');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_links');
        Schema::dropIfExists('statistics');
        Schema::dropIfExists('list_items');
        Schema::dropIfExists('site_contents');
        Schema::dropIfExists('banners');
    }
};
