<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schedule_drafts', function (Blueprint $table) {

            $table->unsignedBigInteger('faculty_id')
                ->nullable()
                ->after('room_id');

            $table->unsignedBigInteger('department_id')
                ->nullable()
                ->after('faculty_id');

            $table->index('faculty_id');
            $table->index('department_id');
        });
    }

    public function down(): void
    {
        Schema::table('schedule_drafts', function (Blueprint $table) {

            $table->dropIndex(
                'schedule_drafts_faculty_id_index'
            );

            $table->dropIndex(
                'schedule_drafts_department_id_index'
            );

            $table->dropColumn([
                'faculty_id',
                'department_id'
            ]);
        });
    }
};