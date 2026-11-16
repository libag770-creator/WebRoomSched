<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('faculty_subjects', function (Blueprint $table) {
            $table->unsignedInteger('student_count')->default(0)->after('year_level');
        });
    }

    public function down(): void
    {
        Schema::table('faculty_subjects', function (Blueprint $table) {
            $table->dropColumn('student_count');
        });
    }
};
