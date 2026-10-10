<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | ADD FACULTY SUBJECT ID
        |--------------------------------------------------------------------------
        */

        if (!Schema::hasColumn('reservations', 'faculty_subject_id')) {
            Schema::table('reservations', function (Blueprint $table) {
                $table->foreignId('faculty_subject_id')
                    ->nullable()
                    ->after('schedule_id')
                    ->constrained('faculty_subjects')
                    ->nullOnDelete();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | ADD EXPIRED STATUS SUPPORT
        |--------------------------------------------------------------------------
        |
        | MySQL ENUM columns require the existing values to be recreated.
        |
        */

        if (Schema::hasColumn('reservations', 'status')) {
            Schema::table('reservations', function (Blueprint $table) {
                $table->string('status', 50)
                    ->default('Pending')
                    ->change();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('reservations', 'faculty_subject_id')) {
            Schema::table('reservations', function (Blueprint $table) {
                $table->dropForeign(['faculty_subject_id']);
                $table->dropColumn('faculty_subject_id');
            });
        }
    }
};