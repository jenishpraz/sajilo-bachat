<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_applications', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no', 30)->unique();
            $table->string('full_name');
            $table->date('date_of_birth');
            $table->string('gender', 20);
            $table->string('phone', 30);
            $table->string('email')->nullable();
            $table->string('permanent_address');
            $table->string('current_address')->nullable();
            $table->string('occupation')->nullable();
            $table->foreignId('membership_type_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->text('message')->nullable();
            $table->string('document')->nullable();   // stored on the private disk
            $table->string('status', 20)->default('pending');
            $table->text('admin_note')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
            $table->index(['status', 'created_at']);
            $table->index('phone');
        });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone', 30)->nullable();
            $table->string('subject');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
            $table->index(['is_read', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('membership_applications');
    }
};
