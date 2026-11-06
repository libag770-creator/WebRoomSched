<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('programs')) {
            return;
        }

        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->string('name', 200);
            $table->string('code', 40);
            $table->string('academic_year', 20);
            $table->string('major', 120)->nullable();
            $table->timestamps();
            $table->index(['department_id', 'academic_year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
