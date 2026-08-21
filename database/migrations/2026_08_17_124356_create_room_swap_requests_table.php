<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_swap_requests', function (Blueprint $table) {

            $table->id();

            // Faculty A - person requesting the swap
            $table->foreignId('requester_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Faculty B - owner of the other schedule/reservation
            $table->foreignId('target_user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Faculty A's schedule, if the booking comes from schedules
            $table->foreignId('requester_schedule_id')
                ->nullable()
                ->constrained('schedules')
                ->nullOnDelete();

            // Faculty B's schedule, if the booking comes from schedules
            $table->foreignId('target_schedule_id')
                ->nullable()
                ->constrained('schedules')
                ->nullOnDelete();

            // Faculty A's room
            $table->foreignId('requester_room_id')
                ->constrained('rooms')
                ->cascadeOnDelete();

            // Faculty B's room
            $table->foreignId('target_room_id')
                ->constrained('rooms')
                ->cascadeOnDelete();

            // Date of the temporary swap
            $table->date('swap_date');

            // Time period of the temporary swap
            $table->time('start_time');
            $table->time('end_time');

            // Reason
            $table->string('reason')->nullable();

            // pending, approved, declined, cancelled
            $table->string('status')->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_swap_requests');
    }
};