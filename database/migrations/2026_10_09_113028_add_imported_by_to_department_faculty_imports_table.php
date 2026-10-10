
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('department_faculty_imports', function (Blueprint $table) {
            $table->unsignedBigInteger('imported_by')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('department_faculty_imports', function (Blueprint $table) {
            $table->dropColumn('imported_by');
        });
    }
};
