<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('committee_members', function (Blueprint $table) {
            $table->enum('gender', ['monk', 'male', 'female'])->nullable()->after('id');
            $table->string('first_name_lo', 100)->nullable()->after('gender');
            $table->string('first_name_en', 100)->nullable()->after('first_name_lo');
            $table->string('first_name_zh', 100)->nullable()->after('first_name_en');
            $table->string('last_name_lo', 100)->nullable()->after('first_name_zh');
            $table->string('last_name_en', 100)->nullable()->after('last_name_lo');
            $table->string('last_name_zh', 100)->nullable()->after('last_name_en');
            $table->date('date_of_birth')->nullable()->after('phone');
            $table->date('date_of_ordination')->nullable()->after('date_of_birth');
            $table->smallInteger('pansa')->unsigned()->nullable()->after('date_of_ordination');
            $table->string('education_lo', 300)->nullable()->after('pansa');
            $table->string('education_en', 300)->nullable()->after('education_lo');
            $table->string('education_zh', 300)->nullable()->after('education_en');
            $table->string('department_lo', 200)->nullable()->after('department');
            $table->string('department_en', 200)->nullable()->after('department_lo');
            $table->string('department_zh', 200)->nullable()->after('department_en');
            $table->string('birth_village_lo', 200)->nullable()->after('department_zh');
            $table->string('birth_village_en', 200)->nullable()->after('birth_village_lo');
            $table->string('birth_village_zh', 200)->nullable()->after('birth_village_en');
            $table->string('district_lo', 100)->nullable()->after('birth_village_zh');
            $table->string('district_en', 100)->nullable()->after('district_lo');
            $table->string('district_zh', 100)->nullable()->after('district_en');
            $table->string('province_lo', 100)->nullable()->after('district_zh');
            $table->string('province_en', 100)->nullable()->after('province_lo');
            $table->string('province_zh', 100)->nullable()->after('province_en');
            $table->string('current_temple_lo', 300)->nullable()->after('province_zh');
            $table->string('current_temple_en', 300)->nullable()->after('current_temple_lo');
            $table->string('current_temple_zh', 300)->nullable()->after('current_temple_en');
            $table->string('facebook', 300)->nullable()->after('current_temple_zh');
        });
    }

    public function down(): void
    {
        Schema::table('committee_members', function (Blueprint $table) {
            $table->dropColumn([
                'gender',
                'first_name_lo', 'first_name_en', 'first_name_zh',
                'last_name_lo', 'last_name_en', 'last_name_zh',
                'date_of_birth', 'date_of_ordination', 'pansa',
                'education_lo', 'education_en', 'education_zh',
                'department_lo', 'department_en', 'department_zh',
                'birth_village_lo', 'birth_village_en', 'birth_village_zh',
                'district_lo', 'district_en', 'district_zh',
                'province_lo', 'province_en', 'province_zh',
                'current_temple_lo', 'current_temple_en', 'current_temple_zh',
                'facebook',
            ]);
        });
    }
};
