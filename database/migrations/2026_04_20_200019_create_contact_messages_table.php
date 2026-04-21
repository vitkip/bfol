<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('email', 120);
            $table->string('phone', 50)->nullable();
            $table->string('subject', 300)->nullable();
            $table->text('message');
            $table->enum('language', ['lo', 'en', 'zh'])->default('lo');
            $table->boolean('is_read')->default(false);
            $table->unsignedBigInteger('replied_by')->nullable();
            $table->dateTime('replied_at')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('is_read');
            $table->index('created_at');
            $table->foreign('replied_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};
