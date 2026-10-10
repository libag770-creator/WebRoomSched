<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('curriculum_grid_layouts')) {
            return;
        }

        Schema::create('curriculum_grid_layouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curriculum_id')
                ->unique()
                ->constrained('curricula')
                ->cascadeOnDelete();
            $table->json('layout_json');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('curriculum_grid_layouts');
    }
};
