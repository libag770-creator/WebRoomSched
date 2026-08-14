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
    Schema::table('rooms', function (Blueprint $table) {

        $table->unsignedInteger('capacity')
            ->nullable()
            ->after('room_name');

        $table->boolean('has_tv')
            ->default(false)
            ->after('capacity');

        $table->boolean('has_projector')
            ->default(false)
            ->after('has_tv');

        $table->unsignedInteger('computers')
            ->default(0)
            ->after('has_projector');

        $table->string('purpose')
            ->nullable()
            ->after('computers');

        $table->text('description')
            ->nullable()
            ->after('purpose');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('rooms', function (Blueprint $table) {

        $table->dropColumn([
            'capacity',
            'has_tv',
            'has_projector',
            'computers',
            'purpose',
            'description',
        ]);

    });
}
};
