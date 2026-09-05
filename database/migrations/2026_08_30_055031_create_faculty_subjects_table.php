<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faculty_subjects', function (Blueprint $table) {
            $table->id();

            $table->foreignId('faculty_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('course_code', 50);

            $table->string('subject', 255);

            $table->string('year_level', 50);

            $table->timestamps();

            // A faculty member cannot have the same course code twice.
            $table->unique(
                ['faculty_id', 'course_code']
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faculty_subjects');
    }
};