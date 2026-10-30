<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group', 40)->default('general')->index();
            $table->timestamps();
        });

        Schema::create('ui_texts', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('en')->nullable();
            $table->text('ne')->nullable();
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('type', 30);
            $table->string('name_en');
            $table->string('name_ne')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->index(['type', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
        Schema::dropIfExists('ui_texts');
        Schema::dropIfExists('settings');
    }
};
