<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('faculty_subjects', 'subject_id')) {
            Schema::table('faculty_subjects', function (Blueprint $table) {
                $table->foreignId('subject_id')->nullable()->after('faculty_id')
                    ->constrained('subjects')->nullOnDelete();
            });
        }

        if (!Schema::hasColumn('faculty_subjects', 'subject_type')) {
            Schema::table('faculty_subjects', function (Blueprint $table) {
                $table->string('subject_type', 20)->nullable()->after('year_level');
            });
        }

        // MySQL may be using the old composite unique index to support the
        // faculty_id foreign key. Give that FK its own index before dropping
        // the old unique constraint.
        $indexes = collect(DB::select('SHOW INDEX FROM faculty_subjects'));
        $facultyIdIndexExists = $indexes->contains(
            fn ($index) => $index->Key_name === 'faculty_subjects_faculty_id_index'
        );

        if (!$facultyIdIndexExists) {
            Schema::table('faculty_subjects', function (Blueprint $table) {
                $table->index('faculty_id', 'faculty_subjects_faculty_id_index');
            });
        }

        $indexes = collect(DB::select('SHOW INDEX FROM faculty_subjects'));
        $oldUniqueExists = $indexes->contains(
            fn ($index) => $index->Key_name === 'faculty_subjects_faculty_id_course_code_unique'
        );

        if ($oldUniqueExists) {
            Schema::table('faculty_subjects', function (Blueprint $table) {
                $table->dropUnique('faculty_subjects_faculty_id_course_code_unique');
            });
        }

        $indexes = collect(DB::select('SHOW INDEX FROM faculty_subjects'));
        $newUniqueExists = $indexes->contains(
            fn ($index) => $index->Key_name === 'faculty_subjects_faculty_id_subject_id_unique'
        );

        if (!$newUniqueExists) {
            Schema::table('faculty_subjects', function (Blueprint $table) {
                $table->unique(['faculty_id', 'subject_id']);
            });
        }

        foreach (['schedules', 'schedule_drafts'] as $tableName) {
            if (!Schema::hasTable($tableName) || Schema::hasColumn($tableName, 'faculty_subject_id')) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) {
                $table->foreignId('faculty_subject_id')->nullable()->after('faculty_id')
                    ->constrained('faculty_subjects')->nullOnDelete();
            });
        }

        // The catalog-import migration may have run before this migration.
        // Backfill schedule links now, while preserving ambiguous matches as
        // null for manual selection rather than linking the wrong subject.
        $this->backfillScheduleSubjectLinks();
    }

    private function backfillScheduleSubjectLinks(): void
    {
        foreach (['schedules', 'schedule_drafts'] as $table) {
            if (!Schema::hasTable($table) || !Schema::hasColumn($table, 'faculty_subject_id')) {
                continue;
            }

            $columns = ['id', 'faculty_id', 'course_code'];
            foreach (['year_level', 'program', 'major'] as $optionalColumn) {
                if (Schema::hasColumn($table, $optionalColumn)) {
                    $columns[] = $optionalColumn;
                }
            }

            $rows = DB::table($table)
                ->whereNull('faculty_subject_id')
                ->whereNotNull('faculty_id')
                ->whereNotNull('course_code')
                ->get($columns);

            foreach ($rows as $row) {
                $courseCode = strtoupper(trim((string) $row->course_code));
                if ($courseCode === '') {
                    continue;
                }

                $candidates = DB::table('faculty_subjects')
                    ->where('faculty_id', $row->faculty_id)
                    ->whereRaw('UPPER(course_code) = ?', [$courseCode])
                    ->get(['id', 'year_level', 'program', 'major']);

                $matching = $candidates->filter(function ($candidate) use ($row) {
                    foreach (['year_level', 'program', 'major'] as $field) {
                        if (!isset($row->{$field}) || trim((string) $row->{$field}) === '') {
                            continue;
                        }

                        if (trim((string) ($candidate->{$field} ?? '')) !== trim((string) $row->{$field})) {
                            return false;
                        }
                    }

                    return true;
                });

                $match = $matching->count() === 1
                    ? $matching->first()
                    : ($candidates->count() === 1 ? $candidates->first() : null);

                if ($match) {
                    DB::table($table)
                        ->where('id', $row->id)
                        ->update(['faculty_subject_id' => $match->id]);
                }
            }
        }
    }

    public function down(): void
    {
        // Keep existing faculty assignments and schedule links intact on rollback.
    }
};
