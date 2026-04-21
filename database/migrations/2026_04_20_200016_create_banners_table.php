<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title_lo', 200);
            $table->string('title_en', 200)->nullable();
            $table->string('title_zh', 200)->nullable();
            $table->text('subtitle_lo')->nullable();
            $table->text('subtitle_en')->nullable();
            $table->text('subtitle_zh')->nullable();
            $table->string('image_url', 500)->nullable();
            $table->string('btn_text_lo', 80)->nullable();
            $table->string('btn_text_en', 80)->nullable();
            $table->string('btn_text_zh', 80)->nullable();
            $table->string('btn_url', 500)->nullable();
            $table->string('style', 50)->default('banner-blue');
            $table->string('position', 80)->default('sidebar');
            $table->smallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'position', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
