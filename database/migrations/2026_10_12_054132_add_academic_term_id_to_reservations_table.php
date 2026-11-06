<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('reservations', 'academic_term_id')) {
            Schema::table('reservations', function (Blueprint $table) {
                $table->foreignId('academic_term_id')
                    ->nullable()
                    ->after('schedule_id')
                    ->constrained('academic_terms')
                    ->nullOnDelete();
            });
        }

        if (
            Schema::hasColumn('reservations', 'schedule_id') &&
            Schema::hasColumn('schedules', 'academic_term_id')
        ) {
            DB::table('reservations')
                ->join('schedules', 'reservations.schedule_id', '=', 'schedules.id')
                ->whereNull('reservations.academic_term_id')
                ->whereNotNull('schedules.academic_term_id')
                ->update([
                    'reservations.academic_term_id' => DB::raw('schedules.academic_term_id'),
                ]);
        }

        $activeTermId = DB::table('academic_terms')
            ->where('is_active', true)
            ->orderByDesc('id')
            ->value('id');

        if ($activeTermId) {
            DB::table('reservations')
                ->whereNull('academic_term_id')
                ->update(['academic_term_id' => $activeTermId]);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('reservations', 'academic_term_id')) {
            Schema::table('reservations', function (Blueprint $table) {
                $table->dropForeign(['academic_term_id']);
                $table->dropColumn('academic_term_id');
            });
        }
    }
};
