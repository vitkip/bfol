<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monk_exchange_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('program_id');
            $table->string('monk_name_lo', 200);
            $table->string('monk_name_en', 200)->nullable();
            $table->string('temple_name_lo', 200)->nullable();
            $table->string('province', 100)->nullable();
            $table->string('phone', 50)->nullable();
            $table->smallInteger('years_ordained')->nullable();
            $table->string('languages', 200)->nullable();
            $table->string('documents_url', 500)->nullable();
            $table->enum('status', ['pending', 'reviewing', 'approved', 'rejected', 'withdrawn'])->default('pending');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->dateTime('reviewed_at')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->index('program_id');
            $table->index('status');
            $table->index('reviewed_by');
            $table->foreign('program_id')->references('id')->on('monk_exchange_programs')->cascadeOnDelete();
            $table->foreign('reviewed_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monk_exchange_applications');
    }
};
