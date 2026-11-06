<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('room_swap_requests', function (Blueprint $table) {
            $table->string('swap_type', 20)->default('temporary')->after('target_room_id');
            $table->date('swap_date')->nullable()->change();
            $table->time('start_time')->nullable()->change();
            $table->time('end_time')->nullable()->change();
        });
    }

    public function down(): void
    {
        DB::table('room_swap_requests')->whereNull('swap_date')->update(['swap_date' => now()->toDateString()]);
        DB::table('room_swap_requests')->whereNull('start_time')->update(['start_time' => '00:00:00']);
        DB::table('room_swap_requests')->whereNull('end_time')->update(['end_time' => '00:01:00']);

        Schema::table('room_swap_requests', function (Blueprint $table) {
            $table->date('swap_date')->nullable(false)->change();
            $table->time('start_time')->nullable(false)->change();
            $table->time('end_time')->nullable(false)->change();
            $table->dropColumn('swap_type');
        });
    }
};
