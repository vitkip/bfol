<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title_lo', 300);
            $table->string('title_en', 300)->nullable();
            $table->string('title_zh', 300)->nullable();
            $table->string('slug', 300)->unique();
            $table->text('excerpt_lo')->nullable();
            $table->text('excerpt_en')->nullable();
            $table->text('excerpt_zh')->nullable();
            $table->longText('content_lo');
            $table->longText('content_en')->nullable();
            $table->longText('content_zh')->nullable();
            $table->string('thumbnail', 500)->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('author_id')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_urgent')->default(false);
            $table->unsignedInteger('view_count')->default(0);
            $table->dateTime('published_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'published_at']);
            $table->index('category_id');
            $table->index('author_id');
            $table->index(['is_featured', 'status', 'published_at']);
            $table->foreign('category_id')->references('id')->on('categories')->nullOnDelete();
            $table->foreign('author_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('news_tags', function (Blueprint $table) {
            $table->unsignedBigInteger('news_id');
            $table->unsignedBigInteger('tag_id');
            $table->primary(['news_id', 'tag_id']);
            $table->foreign('news_id')->references('id')->on('news')->cascadeOnDelete();
            $table->foreign('tag_id')->references('id')->on('tags')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_tags');
        Schema::dropIfExists('news');
    }
};
