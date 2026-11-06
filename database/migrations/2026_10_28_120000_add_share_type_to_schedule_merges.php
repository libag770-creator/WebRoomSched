<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schedule_merges', function (Blueprint $table) {
            $table->string('share_type', 30)->default('merge')->after('status')->index();
        });
    }

    public function down(): void
    {
        Schema::table('schedule_merges', function (Blueprint $table) {
            $table->dropIndex(['share_type']);
            $table->dropColumn('share_type');
        });
    }
};
