<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('subjects')) {
            return;
        }

        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->cascadeOnDelete();
            $table->string('year_level', 30);
            $table->string('course_code', 50);
            $table->string('course_title', 255);
            $table->string('major', 120)->nullable();
            $table->timestamps();
            $table->index(['program_id', 'year_level', 'course_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
