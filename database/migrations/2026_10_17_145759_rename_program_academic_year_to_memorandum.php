<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('programs')) {
            return;
        }

        if (Schema::hasColumn('programs', 'academic_year') && !Schema::hasColumn('programs', 'memorandum')) {
            Schema::table('programs', function (Blueprint $table) {
                $table->renameColumn('academic_year', 'memorandum');
            });
        }

        if (Schema::hasColumn('programs', 'memorandum')) {
            Schema::table('programs', function (Blueprint $table) {
                $table->string('memorandum', 500)->change();
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('programs')) {
            return;
        }

        if (Schema::hasColumn('programs', 'memorandum') && !Schema::hasColumn('programs', 'academic_year')) {
            Schema::table('programs', function (Blueprint $table) {
                $table->renameColumn('memorandum', 'academic_year');
            });
        }

        if (Schema::hasColumn('programs', 'academic_year')) {
            Schema::table('programs', function (Blueprint $table) {
                $table->string('academic_year', 500)->change();
            });
        }
    }
};
