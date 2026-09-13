<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('schedule_merges', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | ORIGINAL / OWNER SCHEDULE
            |--------------------------------------------------------------------------
            */

            $table->foreignId('schedule_id')
                ->constrained('schedules')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | DEPARTMENT REQUESTING THE MERGE
            |--------------------------------------------------------------------------
            */

            $table->foreignId('department_id')
                ->constrained('departments')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | SUBJECT OF THE DEPARTMENT REQUESTING THE MERGE
            |--------------------------------------------------------------------------
            */

            $table->foreignId('faculty_subject_id')
                ->constrained('faculty_subjects')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | MERGE STATUS
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'pending',
                'approved',
                'rejected'
            ])->default('pending');


            /*
            |--------------------------------------------------------------------------
            | APPROVAL INFORMATION
            |--------------------------------------------------------------------------
            */

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('approved_at')
                ->nullable();


            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | PREVENT DUPLICATE MERGE REQUESTS
            |--------------------------------------------------------------------------
            */

            $table->unique([
                'schedule_id',
                'department_id'
            ]);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_merges');
    }
};