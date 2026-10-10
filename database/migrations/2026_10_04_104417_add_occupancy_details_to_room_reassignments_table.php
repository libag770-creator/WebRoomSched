
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('room_reassignments', function (Blueprint $table) {
            $table->string('occupancy_decision')->nullable();
            $table->string('occupied_by')->nullable();
            $table->string('occupancy_purpose')->nullable();
            $table->text('occupancy_details')->nullable();
            $table->string('not_occupied_reason')->nullable();
            $table->text('not_occupied_details')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('room_reassignments', function (Blueprint $table) {
            $table->dropColumn([
                'occupancy_decision',
                'occupied_by',
                'occupancy_purpose',
                'occupancy_details',
                'not_occupied_reason',
                'not_occupied_details',
            ]);
        });
    }
};
