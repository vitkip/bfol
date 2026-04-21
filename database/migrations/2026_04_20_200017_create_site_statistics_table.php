<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_statistics', function (Blueprint $table) {
            $table->id();
            $table->string('label_lo', 100);
            $table->string('label_en', 100)->nullable();
            $table->string('label_zh', 100)->nullable();
            $table->unsignedInteger('value')->default(0);
            $table->string('icon', 100)->nullable();
            $table->string('suffix', 20)->nullable();
            $table->smallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_statistics');
    }
};
