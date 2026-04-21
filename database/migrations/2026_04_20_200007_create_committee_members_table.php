<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('committee_members', function (Blueprint $table) {
            $table->id();
            $table->string('name_lo', 200);
            $table->string('name_en', 200)->nullable();
            $table->string('name_zh', 200)->nullable();
            $table->string('title_lo', 100)->nullable();
            $table->string('title_en', 100)->nullable();
            $table->string('title_zh', 100)->nullable();
            $table->string('position_lo', 200);
            $table->string('position_en', 200)->nullable();
            $table->string('position_zh', 200)->nullable();
            $table->string('department', 200)->nullable();
            $table->string('photo_url', 500)->nullable();
            $table->text('bio_lo')->nullable();
            $table->text('bio_en')->nullable();
            $table->text('bio_zh')->nullable();
            $table->string('email', 120)->nullable();
            $table->string('phone', 50)->nullable();
            $table->year('term_start')->nullable();
            $table->year('term_end')->nullable();
            $table->smallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('committee_members');
    }
};
