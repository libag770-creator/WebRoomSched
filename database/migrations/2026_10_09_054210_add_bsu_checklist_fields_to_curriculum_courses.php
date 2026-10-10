<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('curriculum_courses')) {
            return;
        }

        Schema::table('curriculum_courses', function (Blueprint $table) {
            if (!Schema::hasColumn('curriculum_courses', 'lec_units')) {
                $table->decimal('lec_units', 8, 2)->nullable()->after('units');
            }
            if (!Schema::hasColumn('curriculum_courses', 'lab_units')) {
                $table->decimal('lab_units', 8, 2)->nullable()->after('lec_units');
            }
            if (!Schema::hasColumn('curriculum_courses', 'lec_hours')) {
                $table->decimal('lec_hours', 8, 2)->nullable()->after('lab_units');
            }
            if (!Schema::hasColumn('curriculum_courses', 'lab_hours')) {
                $table->decimal('lab_hours', 8, 2)->nullable()->after('lec_hours');
            }
            if (!Schema::hasColumn('curriculum_courses', 'grade')) {
                $table->string('grade', 20)->nullable()->after('lab_hours');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('curriculum_courses')) {
            return;
        }

        $drop = [];
        foreach (['lec_units','lab_units','lec_hours','lab_hours','grade'] as $column) {
            if (Schema::hasColumn('curriculum_courses', $column)) {
                $drop[] = $column;
            }
        }

        if ($drop) {
            Schema::table('curriculum_courses', function (Blueprint $table) use ($drop) {
                $table->dropColumn($drop);
            });
        }
    }
};
