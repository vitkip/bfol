<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();
            $table->string('tag_lo', 100)->nullable();
            $table->string('tag_en', 100)->nullable();
            $table->string('tag_zh', 100)->nullable();
            $table->string('title_lo', 300);
            $table->string('title_en', 300)->nullable();
            $table->string('title_zh', 300)->nullable();
            $table->text('subtitle_lo')->nullable();
            $table->text('subtitle_en')->nullable();
            $table->text('subtitle_zh')->nullable();
            $table->string('image_url', 500);
            $table->string('btn1_text_lo', 80)->nullable();
            $table->string('btn1_text_en', 80)->nullable();
            $table->string('btn1_text_zh', 80)->nullable();
            $table->string('btn1_url', 500)->nullable();
            $table->string('btn2_text_lo', 80)->nullable();
            $table->string('btn2_text_en', 80)->nullable();
            $table->string('btn2_text_zh', 80)->nullable();
            $table->string('btn2_url', 500)->nullable();
            $table->smallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_slides');
    }
};
