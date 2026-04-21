<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->longText('value')->nullable();
            $table->enum('type', ['text', 'textarea', 'image', 'json', 'boolean', 'number', 'color'])->default('text');
            $table->string('group', 80)->default('general');
            $table->string('label_lo', 200)->nullable();
            $table->string('label_en', 200)->nullable();
            $table->string('label_zh', 200)->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
