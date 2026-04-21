<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mou_agreements', function (Blueprint $table) {
            $table->id();
            $table->string('title_lo', 300);
            $table->string('title_en', 300)->nullable();
            $table->string('title_zh', 300)->nullable();
            $table->unsignedBigInteger('partner_org_id');
            $table->date('signed_date');
            $table->date('expiry_date')->nullable();
            $table->string('document_url', 500)->nullable();
            $table->enum('status', ['active', 'expired', 'pending', 'renewed', 'terminated'])->default('active');
            $table->text('description_lo')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_zh')->nullable();
            $table->text('signers_lo')->nullable();
            $table->text('signers_en')->nullable();
            $table->text('signers_zh')->nullable();
            $table->text('scope_lo')->nullable();
            $table->text('scope_en')->nullable();
            $table->text('scope_zh')->nullable();
            $table->timestamps();

            $table->index('partner_org_id');
            $table->index('status');
            $table->index(['expiry_date', 'status']);
            $table->foreign('partner_org_id')->references('id')->on('partner_organizations')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mou_agreements');
    }
};
