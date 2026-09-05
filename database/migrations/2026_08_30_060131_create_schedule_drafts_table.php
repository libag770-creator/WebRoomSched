<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedule_drafts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('room_id')
                ->constrained('rooms')
                ->cascadeOnDelete();

            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('day', 20);

            $table->string('time', 50);

            $table->string('course_code', 50)->nullable();

            $table->string('subject', 255);

            $table->string('instructor', 255);

            $table->string('description')->nullable();

            $table->string('color', 20)
                ->default('#ffffff');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_drafts');
    }
};