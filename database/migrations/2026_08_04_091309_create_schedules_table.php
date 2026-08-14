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
    Schema::create('schedules', function (Blueprint $table) {

        $table->id();

        $table->foreignId('room_id')
      ->constrained()
      ->cascadeOnDelete();
      
        $table->string('day');

        $table->string('time');

        $table->string('subject')->nullable();

        $table->string('course_code')->nullable();

        $table->string('instructor')->nullable();

        $table->text('description')->nullable();
        
        $table->string('color')->default('#ffffff');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
