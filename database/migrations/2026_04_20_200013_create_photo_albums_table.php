<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('photo_albums', function (Blueprint $table) {
            $table->id();
            $table->string('title_lo', 200);
            $table->string('title_en', 200)->nullable();
            $table->string('title_zh', 200)->nullable();
            $table->text('description_lo')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_zh')->nullable();
            $table->string('cover_image', 500)->nullable();
            $table->unsignedBigInteger('event_id')->nullable();
            $table->boolean('is_public')->default(true);
            $table->timestamps();

            $table->index('event_id');
            $table->foreign('event_id')->references('id')->on('events')->nullOnDelete();
        });

        Schema::create('photo_album_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('album_id');
            $table->string('image_url', 500);
            $table->string('caption_lo', 300)->nullable();
            $table->string('caption_en', 300)->nullable();
            $table->string('caption_zh', 300)->nullable();
            $table->smallInteger('sort_order')->default(0);
            $table->timestamp('created_at')->useCurrent();

            $table->index('album_id');
            $table->foreign('album_id')->references('id')->on('photo_albums')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photo_album_images');
        Schema::dropIfExists('photo_albums');
    }
};
