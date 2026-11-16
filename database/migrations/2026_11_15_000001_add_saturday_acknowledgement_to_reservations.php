<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            if (!Schema::hasColumn('reservations', 'saturday_approval_acknowledged_at')) {
                $table->timestamp('saturday_approval_acknowledged_at')->nullable();
            }
            if (!Schema::hasColumn('reservations', 'saturday_approval_acknowledged_by')) {
                $table->unsignedBigInteger('saturday_approval_acknowledged_by')->nullable()->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            if (Schema::hasColumn('reservations', 'saturday_approval_acknowledged_by')) {
                $table->dropIndex(['saturday_approval_acknowledged_by']);
                $table->dropColumn('saturday_approval_acknowledged_by');
            }
            if (Schema::hasColumn('reservations', 'saturday_approval_acknowledged_at')) {
                $table->dropColumn('saturday_approval_acknowledged_at');
            }
        });
    }
};
