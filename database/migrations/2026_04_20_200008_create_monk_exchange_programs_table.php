<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monk_exchange_programs', function (Blueprint $table) {
            $table->id();
            $table->string('title_lo', 300);
            $table->string('title_en', 300)->nullable();
            $table->string('title_zh', 300)->nullable();
            $table->string('destination_country', 100);
            $table->unsignedBigInteger('partner_org_id')->nullable();
            $table->year('year');
            $table->date('application_open')->nullable();
            $table->date('application_deadline')->nullable();
            $table->date('program_start')->nullable();
            $table->date('program_end')->nullable();
            $table->smallInteger('monks_quota')->nullable();
            $table->smallInteger('monks_selected')->default(0);
            $table->text('description_lo')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_zh')->nullable();
            $table->text('requirements_lo')->nullable();
            $table->text('requirements_en')->nullable();
            $table->text('requirements_zh')->nullable();
            $table->string('application_url', 500)->nullable();
            $table->string('contact_email', 120)->nullable();
            $table->enum('status', ['draft', 'open', 'closed', 'ongoing', 'completed', 'cancelled'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->unsignedBigInteger('author_id')->nullable();
            $table->timestamps();

            $table->index('year');
            $table->index('status');
            $table->index('partner_org_id');
            $table->index('author_id');
            $table->foreign('partner_org_id')->references('id')->on('partner_organizations')->nullOnDelete();
            $table->foreign('author_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monk_exchange_programs');
    }
};
