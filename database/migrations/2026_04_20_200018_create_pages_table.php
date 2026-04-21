<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 150)->unique();
            $table->string('title_lo', 300);
            $table->string('title_en', 300)->nullable();
            $table->string('title_zh', 300)->nullable();
            $table->longText('content_lo')->nullable();
            $table->longText('content_en')->nullable();
            $table->longText('content_zh')->nullable();
            $table->string('meta_title_lo', 200)->nullable();
            $table->string('meta_title_en', 200)->nullable();
            $table->string('meta_title_zh', 200)->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('thumbnail', 500)->nullable();
            $table->string('parent_slug', 150)->nullable();
            $table->smallInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->unsignedBigInteger('author_id')->nullable();
            $table->timestamps();

            $table->index('author_id');
            $table->foreign('author_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
