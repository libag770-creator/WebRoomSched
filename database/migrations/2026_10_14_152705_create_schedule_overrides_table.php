<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedule_overrides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained('schedules')->cascadeOnDelete();
            $table->foreignId('faculty_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('override_date');
            $table->string('reason', 255);
            $table->string('occupied_by', 255);
            $table->text('details')->nullable();
            $table->timestamp('expires_at');
            $table->string('status', 20)->default('active');
            $table->timestamps();

            $table->index(['schedule_id', 'override_date', 'status'], 'schedule_overrides_schedule_date_status_idx');
            $table->index(['faculty_id', 'override_date'], 'schedule_overrides_faculty_date_idx');
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_overrides');
    }
};
