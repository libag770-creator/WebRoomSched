<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedule_time_slots', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | ACADEMIC TERM
            |--------------------------------------------------------------------------
            */
            $table->foreignId('academic_term_id')
                ->nullable()
                ->constrained('academic_terms')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | DAY
            |--------------------------------------------------------------------------
            |
            | MON, TUE, WED, THU, FRI, SAT
            |
            */
            $table->string('day', 10);

            /*
            |--------------------------------------------------------------------------
            | TIME
            |--------------------------------------------------------------------------
            */
            $table->time('start_time');
            $table->time('end_time');

            /*
            |--------------------------------------------------------------------------
            | POSITION IN CHAIR TIMETABLE
            |--------------------------------------------------------------------------
            */
            $table->unsignedInteger('row_position')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | ACTIVE / INACTIVE
            |--------------------------------------------------------------------------
            */
            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | INDEXES
            |--------------------------------------------------------------------------
            */
          $table->index(
    ['academic_term_id', 'day', 'start_time', 'end_time'],
    'sts_term_day_time_idx'
);

          $table->index(
    ['academic_term_id', 'day', 'row_position'],
    'sts_term_day_row_idx'
);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_time_slots');
    }
};