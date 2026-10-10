<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('reservations')) {
            return;
        }

        // Existing approval-based reservations remain active.
        // They are converted to the new single active status.
        DB::table('reservations')
            ->whereIn('status', ['Pending', 'Approved'])
            ->update(['status' => 'Reserved']);

        if (Schema::hasColumn('reservations', 'status')) {
            Schema::table('reservations', function (Blueprint $table) {
                $table->string('status', 50)
                    ->default('Reserved')
                    ->change();
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('reservations')) {
            return;
        }

        DB::table('reservations')
            ->where('status', 'Reserved')
            ->update(['status' => 'Approved']);

        if (Schema::hasColumn('reservations', 'status')) {
            Schema::table('reservations', function (Blueprint $table) {
                $table->string('status', 50)
                    ->default('Pending')
                    ->change();
            });
        }
    }
};
