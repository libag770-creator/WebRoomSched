<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_reassignments', function (Blueprint $table) {
            $table->id();

            // Original schedule
            $table->foreignId('schedule_id')
                ->constrained('schedules')
                ->cascadeOnDelete();

            // Original room
            $table->foreignId('original_room_id')
                ->constrained('rooms')
                ->cascadeOnDelete();

            // Temporary replacement room
            $table->foreignId('new_room_id')
                ->constrained('rooms')
                ->cascadeOnDelete();

            // The specific day being reassigned
            $table->string('day');

            // Start/end time of the reassigned occurrence
            $table->time('start_time');
            $table->time('end_time');

            // Why the room was reassigned
            $table->string('reason')->nullable();

            // Optional additional explanation
            $table->text('details')->nullable();

            // When the reassignment becomes active
            $table->timestamp('starts_at')->nullable();

            // When the reassignment automatically expires
            $table->timestamp('expires_at')->nullable();

            // active / expired / cancelled
            $table->string('status')->default('active');

            $table->timestamps();

            $table->index([
                'schedule_id',
                'day',
                'start_time',
                'end_time'
            ]);

            $table->index([
                'new_room_id',
                'day',
                'start_time',
                'end_time'
            ]);

            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_reassignments');
    }
};