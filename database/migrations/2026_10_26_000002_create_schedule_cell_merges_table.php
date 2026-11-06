<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedule_cell_merges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();
            $table->foreignId('academic_term_id')->constrained('academic_terms')->cascadeOnDelete();
            $table->string('axis', 12);
            $table->json('cells');
            $table->timestamps();

            $table->index(['room_id', 'academic_term_id'], 'schedule_cell_merges_room_term_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_cell_merges');
    }
};
