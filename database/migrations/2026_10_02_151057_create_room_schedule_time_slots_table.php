<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_schedule_time_slots', function (Blueprint $table) {

            $table->id();

            $table->foreignId('room_id')
                ->constrained('rooms')
                ->cascadeOnDelete();

            $table->foreignId('academic_term_id')
                ->constrained('academic_terms')
                ->cascadeOnDelete();

            $table->string('day', 20);

            $table->time('start_time');

            $table->time('end_time');

            $table->integer('row_position')->default(0);

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique([
                'room_id',
                'academic_term_id',
                'day',
                'start_time',
                'end_time',
            ], 'room_term_day_time_unique');

            $table->index([
                'room_id',
                'academic_term_id',
                'day',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_schedule_time_slots');
    }
};