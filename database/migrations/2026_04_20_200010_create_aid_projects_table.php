<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aid_projects', function (Blueprint $table) {
            $table->id();
            $table->string('title_lo', 300);
            $table->string('title_en', 300)->nullable();
            $table->string('title_zh', 300)->nullable();
            $table->string('country', 100);
            $table->unsignedBigInteger('partner_org_id')->nullable();
            $table->enum('type', ['religious', 'humanitarian', 'educational', 'cultural', 'other'])->default('religious');
            $table->text('description_lo')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_zh')->nullable();
            $table->decimal('budget_usd', 15, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['planning', 'active', 'completed', 'suspended', 'cancelled'])->default('planning');
            $table->string('report_url', 500)->nullable();
            $table->unsignedBigInteger('author_id')->nullable();
            $table->timestamps();

            $table->index('partner_org_id');
            $table->index('author_id');
            $table->index('status');
            $table->foreign('partner_org_id')->references('id')->on('partner_organizations')->nullOnDelete();
            $table->foreign('author_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aid_projects');
    }
};
