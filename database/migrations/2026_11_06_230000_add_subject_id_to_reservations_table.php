<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('reservations', 'subject_id')) {
            Schema::table('reservations', function (Blueprint $table) {
                $table->foreignId('subject_id')
                    ->nullable()
                    ->after('faculty_subject_id')
                    ->constrained('subjects')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('reservations', 'subject_id')) {
            Schema::table('reservations', function (Blueprint $table) {
                $table->dropForeign(['subject_id']);
                $table->dropColumn('subject_id');
            });
        }
    }
};
