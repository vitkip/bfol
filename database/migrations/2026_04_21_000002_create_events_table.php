<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('events')) {
            return;
        }
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title_lo');
            $table->string('title_en');
            $table->string('title_zh');
            $table->string('slug')->unique();
            $table->text('description_lo')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_zh')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('location_lo')->nullable();
            $table->string('location_en')->nullable();
            $table->string('location_zh')->nullable();
            $table->string('country')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('organizer_lo')->nullable();
            $table->string('organizer_en')->nullable();
            $table->string('organizer_zh')->nullable();
            $table->string('registration_url')->nullable();
            $table->date('registration_deadline')->nullable();
            $table->unsignedInteger('max_participants')->nullable();
            $table->string('status')->default('upcoming');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_international')->default(false);
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedBigInteger('view_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
