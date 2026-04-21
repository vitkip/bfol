<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name_lo', 120);
            $table->string('name_en', 120)->nullable();
            $table->string('name_zh', 120)->nullable();
            $table->string('slug', 120)->unique();
            $table->enum('type', ['news', 'event', 'media', 'document', 'mission']);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('color', 10)->default('#1a3a6b');
            $table->string('icon', 80)->nullable();
            $table->smallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->useCurrent();

            $table->index('parent_id');
            $table->foreign('parent_id')->references('id')->on('categories')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
