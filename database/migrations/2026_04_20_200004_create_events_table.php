<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title_lo', 300);
            $table->string('title_en', 300)->nullable();
            $table->string('title_zh', 300)->nullable();
            $table->string('slug', 300)->unique();
            $table->longText('description_lo');
            $table->longText('description_en')->nullable();
            $table->longText('description_zh')->nullable();
            $table->string('thumbnail', 500)->nullable();
            $table->string('location_lo', 300)->nullable();
            $table->string('location_en', 300)->nullable();
            $table->string('location_zh', 300)->nullable();
            $table->string('country', 100)->default('ລາວ');
            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('organizer_lo', 200)->nullable();
            $table->string('organizer_en', 200)->nullable();
            $table->string('organizer_zh', 200)->nullable();
            $table->string('registration_url', 500)->nullable();
            $table->date('registration_deadline')->nullable();
            $table->unsignedInteger('max_participants')->nullable();
            $table->enum('status', ['upcoming', 'ongoing', 'completed', 'cancelled'])->default('upcoming');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_international')->default(false);
            $table->unsignedBigInteger('author_id')->nullable();
            $table->unsignedInteger('view_count')->default(0);
            $table->timestamps();

            $table->index(['start_date', 'end_date']);
            $table->index('status');
            $table->index('category_id');
            $table->index('author_id');
            $table->index(['is_featured', 'status', 'start_date']);
            $table->foreign('category_id')->references('id')->on('categories')->nullOnDelete();
            $table->foreign('author_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('event_tags', function (Blueprint $table) {
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('tag_id');
            $table->primary(['event_id', 'tag_id']);
            $table->foreign('event_id')->references('id')->on('events')->cascadeOnDelete();
            $table->foreign('tag_id')->references('id')->on('tags')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_tags');
        Schema::dropIfExists('events');
    }
};
