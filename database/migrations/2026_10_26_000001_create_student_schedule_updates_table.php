<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_schedule_updates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('academic_term_id')->index();
            $table->unsignedBigInteger('department_id')->index();
            $table->string('program', 100);
            $table->string('year_level', 50);
            $table->unsignedBigInteger('schedule_id')->nullable()->index();
            $table->string('event_type', 50)->index();
            $table->string('title', 160);
            $table->text('message');
            $table->json('payload')->nullable();
            $table->dateTime('effective_at')->nullable();
            $table->dateTime('expires_at')->nullable()->index();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index(
                ['academic_term_id', 'department_id', 'program', 'year_level', 'created_at'],
                'student_schedule_updates_cohort_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_schedule_updates');
    }
};
