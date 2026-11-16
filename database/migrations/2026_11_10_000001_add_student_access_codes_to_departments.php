<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->text('student_access_code')->nullable();
            $table->string('student_access_code_hash', 64)->nullable()->unique();
        });
    }

    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropUnique(['student_access_code_hash']);
            $table->dropColumn(['student_access_code', 'student_access_code_hash']);
        });
    }
};
