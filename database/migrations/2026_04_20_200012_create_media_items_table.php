<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_items', function (Blueprint $table) {
            $table->id();
            $table->string('title_lo', 300);
            $table->string('title_en', 300)->nullable();
            $table->string('title_zh', 300)->nullable();
            $table->enum('type', ['image', 'video', 'audio', 'document']);
            $table->string('file_url', 500)->nullable();
            $table->string('thumbnail_url', 500)->nullable();
            $table->text('description_lo')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_zh')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('event_id')->nullable();
            $table->enum('platform', ['local', 'youtube', 'facebook', 'soundcloud', 'other'])->default('local');
            $table->string('external_url', 500)->nullable();
            $table->unsignedInteger('duration_sec')->nullable();
            $table->unsignedInteger('file_size_kb')->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('view_count')->default(0);
            $table->unsignedInteger('download_count')->default(0);
            $table->dateTime('published_at')->nullable();
            $table->unsignedBigInteger('author_id')->nullable();
            $table->timestamps();

            $table->index('type');
            $table->index('platform');
            $table->index('category_id');
            $table->index('event_id');
            $table->index('author_id');
            $table->index(['is_featured', 'type']);
            $table->foreign('category_id')->references('id')->on('categories')->nullOnDelete();
            $table->foreign('event_id')->references('id')->on('events')->nullOnDelete();
            $table->foreign('author_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_items');
    }
};
