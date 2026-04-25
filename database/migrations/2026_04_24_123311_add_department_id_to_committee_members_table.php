<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('committee_members', function (Blueprint $table) {
            $table->foreignId('department_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('departments')
                  ->nullOnDelete();

            // Remove old string-based department columns
            $table->dropColumn(['department', 'department_lo', 'department_en', 'department_zh']);
        });
    }

    public function down(): void
    {
        Schema::table('committee_members', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');

            $table->string('department', 200)->nullable();
            $table->string('department_lo', 200)->nullable();
            $table->string('department_en', 200)->nullable();
            $table->string('department_zh', 200)->nullable();
        });
    }
};
