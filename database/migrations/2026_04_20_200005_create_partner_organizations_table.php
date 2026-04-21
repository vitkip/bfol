<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partner_organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name_lo', 200);
            $table->string('name_en', 200)->nullable();
            $table->string('name_zh', 200)->nullable();
            $table->string('acronym', 30)->nullable();
            $table->char('country_code', 2);
            $table->string('country_name_lo', 100);
            $table->string('country_name_en', 100)->nullable();
            $table->string('country_name_zh', 100)->nullable();
            $table->string('logo_url', 500)->nullable();
            $table->string('website_url', 500)->nullable();
            $table->text('description_lo')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_zh')->nullable();
            $table->string('contact_person', 200)->nullable();
            $table->string('contact_email', 120)->nullable();
            $table->string('contact_phone', 50)->nullable();
            $table->enum('type', ['buddhist_org', 'government', 'ngo', 'academic', 'media', 'un_agency', 'other'])->default('buddhist_org');
            $table->year('partnership_since')->nullable();
            $table->enum('status', ['active', 'inactive', 'pending'])->default('active');
            $table->smallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('country_code');
            $table->index('status');
            $table->index(['status', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_organizations');
    }
};
