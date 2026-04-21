<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title_lo', 300);
            $table->string('title_en', 300)->nullable();
            $table->string('title_zh', 300)->nullable();
            $table->string('file_url', 500);
            $table->string('file_type', 20)->nullable();
            $table->unsignedInteger('file_size_kb')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->text('description_lo')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_zh')->nullable();
            $table->boolean('is_public')->default(true);
            $table->unsignedInteger('download_count')->default(0);
            $table->dateTime('published_at')->nullable();
            $table->unsignedBigInteger('author_id')->nullable();
            $table->timestamps();

            $table->index('category_id');
            $table->index('author_id');
            $table->index(['is_public', 'published_at']);
            $table->foreign('category_id')->references('id')->on('categories')->nullOnDelete();
            $table->foreign('author_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
