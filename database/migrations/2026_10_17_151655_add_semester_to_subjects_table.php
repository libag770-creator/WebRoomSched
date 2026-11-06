<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('subjects') && !Schema::hasColumn('subjects', 'semester')) {
            Schema::table('subjects', function (Blueprint $table) {
                $table->string('semester', 30)->default('1st Semester')->after('year_level');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('subjects') && Schema::hasColumn('subjects', 'semester')) {
            Schema::table('subjects', function (Blueprint $table) {
                $table->dropColumn('semester');
            });
        }
    }
};
