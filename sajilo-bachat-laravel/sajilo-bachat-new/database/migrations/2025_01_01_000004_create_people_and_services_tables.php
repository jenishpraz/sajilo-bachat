<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->string('group', 30);          // board | employee | former_committee | former_employee
            $table->string('section', 40)->nullable(); // committee / sub-committee key (board page grouping)
            $table->string('name_en');
            $table->string('name_ne')->nullable();
            $table->string('designation_en')->nullable();
            $table->string('designation_ne')->nullable();
            $table->text('bio_en')->nullable();
            $table->text('bio_ne')->nullable();
            $table->string('phone', 40)->nullable();
            $table->string('email')->nullable();
            $table->string('photo')->nullable();
            $table->string('tenure')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['group', 'is_active', 'sort_order']);
        });

        Schema::create('saving_plans', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name_en');
            $table->string('name_ne')->nullable();
            $table->string('summary_en', 400)->nullable();
            $table->string('summary_ne', 400)->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_ne')->nullable();
            $table->decimal('interest_rate', 5, 2)->nullable();
            $table->decimal('min_deposit', 14, 2)->nullable();
            $table->string('duration_en')->nullable();
            $table->string('duration_ne')->nullable();
            $table->text('eligibility_en')->nullable();
            $table->text('eligibility_ne')->nullable();
            $table->text('features_en')->nullable();   // one benefit per line
            $table->text('features_ne')->nullable();
            $table->string('image')->nullable();
            $table->string('icon', 40)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_draft')->default(false);
            $table->timestamps();
            $table->index(['is_active', 'sort_order']);
        });

        Schema::create('loan_services', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name_en');
            $table->string('name_ne')->nullable();
            $table->string('summary_en', 400)->nullable();
            $table->string('summary_ne', 400)->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_ne')->nullable();
            $table->decimal('interest_rate', 5, 2)->nullable();
            $table->decimal('min_amount', 14, 2)->nullable();
            $table->decimal('max_amount', 14, 2)->nullable();
            $table->string('duration_en')->nullable();
            $table->string('duration_ne')->nullable();
            $table->text('eligibility_en')->nullable();
            $table->text('eligibility_ne')->nullable();
            $table->text('documents_en')->nullable();  // one document per line
            $table->text('documents_ne')->nullable();
            $table->text('terms_en')->nullable();
            $table->text('terms_ne')->nullable();
            $table->string('image')->nullable();
            $table->string('icon', 40)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_draft')->default(false);
            $table->timestamps();
            $table->index(['is_active', 'sort_order']);
        });

        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_ne')->nullable();
            $table->string('address_en')->nullable();
            $table->string('address_ne')->nullable();
            $table->string('phone', 60)->nullable();
            $table->string('email')->nullable();
            $table->string('manager_en')->nullable();
            $table->string('manager_ne')->nullable();
            $table->string('opening_hours_en')->nullable();
            $table->string('opening_hours_ne')->nullable();
            $table->string('map_url', 1000)->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_head_office')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_draft')->default(false);
            $table->timestamps();
            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branches');
        Schema::dropIfExists('loan_services');
        Schema::dropIfExists('saving_plans');
        Schema::dropIfExists('people');
    }
};
