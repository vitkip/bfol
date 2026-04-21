<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name_lo', 80);
            $table->string('name_en', 80)->nullable();
            $table->string('name_zh', 80)->nullable();
            $table->string('slug', 80)->unique();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};
