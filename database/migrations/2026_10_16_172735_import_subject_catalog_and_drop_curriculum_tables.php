<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $programIds = $this->importPrograms();
        $this->importCourseRows($programIds);
        $this->importGridSubjects($programIds);
        $this->backfillScheduleSubjectLinks();

        foreach (['faculty_subjects', 'schedules', 'schedule_drafts'] as $table) {
            $this->dropCurriculumCourseColumn($table);
        }

        foreach ([
            'curriculum_documents',
            'curriculum_grid_layouts',
            'curriculum_courses',
            'curricula',
        ] as $table) {
            Schema::dropIfExists($table);
        }
    }

    private function importPrograms(): array
    {
        if (!Schema::hasTable('curricula')) {
            return [];
        }

        $programIds = [];
        foreach (DB::table('curricula')->orderBy('id')->get() as $legacy) {
            $name = trim((string) $legacy->program);
            $legacyCode = trim((string) $legacy->curriculum_code);
            $code = strtoupper(trim(explode('-', $legacyCode)[0] ?: $name));
            $year = trim((string) ($legacy->effective_year ?: ''));
            if ($name === '' || $code === '' || $year === '') {
                continue;
            }

            $major = trim((string) ($legacy->major ?? '')) ?: null;
            $query = DB::table('programs')
                ->where('department_id', $legacy->department_id)
                ->where('name', $name)
                ->where('code', $code)
                ->where('academic_year', $year);
            $major === null ? $query->whereNull('major') : $query->where('major', $major);
            $programId = $query->value('id');

            if (!$programId) {
                $programId = DB::table('programs')->insertGetId([
                    'department_id' => $legacy->department_id,
                    'name' => $name,
                    'code' => $code,
                    'academic_year' => $year,
                    'major' => $major,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $programIds[$legacy->id] = (int) $programId;
        }

        return $programIds;
    }

    private function importCourseRows(array $programIds): void
    {
        if (!Schema::hasTable('curriculum_courses')) {
            return;
        }

        foreach (DB::table('curriculum_courses')->orderBy('id')->get() as $legacy) {
            $programId = $programIds[$legacy->curriculum_id] ?? null;
            $code = strtoupper(trim((string) $legacy->course_code));
            $title = trim((string) $legacy->subject);
            $year = $this->normalizeYear((string) $legacy->year_level);
            if (!$programId || $code === '' || $title === '' || !$year) {
                continue;
            }

            $this->insertSubjectIfMissing($programId, $year, $code, $title);
        }
    }

    private function importGridSubjects(array $programIds): void
    {
        if (!Schema::hasTable('curriculum_grid_layouts')) {
            return;
        }

        foreach (DB::table('curriculum_grid_layouts')->orderBy('id')->get() as $layout) {
            $programId = $programIds[$layout->curriculum_id] ?? null;
            if (!$programId) {
                continue;
            }

            $data = json_decode((string) $layout->layout_json, true);
            if (!is_array($data) || !is_array($data['years'] ?? null)) {
                continue;
            }

            foreach ($data['years'] as $yearLabel => $yearData) {
                $year = $this->normalizeYear((string) $yearLabel);
                if (!$year || !is_array($yearData['rows'] ?? null)) {
                    continue;
                }

                foreach ($yearData['rows'] as $row) {
                    if (!is_array($row)) {
                        continue;
                    }
                    foreach ([1, 7] as $column) {
                        $code = strtoupper(trim((string) ($row[$column] ?? '')));
                        $title = trim((string) ($row[$column + 1] ?? ''));
                        if ($code === '' || $title === '') {
                            continue;
                        }
                        $this->insertSubjectIfMissing($programId, $year, $code, $title);
                    }
                }
            }
        }
    }

    private function insertSubjectIfMissing(int $programId, string $year, string $code, string $title): void
    {
        $query = DB::table('subjects')
            ->where('program_id', $programId)
            ->where('year_level', $year)
            ->whereRaw('UPPER(course_code) = ?', [$code]);
        $query->whereNull('major');
        if ($query->exists()) {
            return;
        }

        DB::table('subjects')->insert([
            'program_id' => $programId,
            'year_level' => $year,
            'course_code' => $code,
            'course_title' => $title,
            'major' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function normalizeYear(string $year): ?string
    {
        $normalized = strtolower(trim($year));
        return match (true) {
            str_contains($normalized, '1st') || $normalized === '1' => '1st Year',
            str_contains($normalized, '2nd') || $normalized === '2' => '2nd Year',
            str_contains($normalized, '3rd') || $normalized === '3' => '3rd Year',
            str_contains($normalized, '4th') || $normalized === '4' => '4th Year',
            default => null,
        };
    }

    private function backfillScheduleSubjectLinks(): void
    {
        foreach (['schedules', 'schedule_drafts'] as $table) {
            if (!Schema::hasTable($table) || !Schema::hasColumn($table, 'faculty_subject_id')) {
                continue;
            }

            $rows = DB::table($table)
                ->whereNull('faculty_subject_id')
                ->whereNotNull('faculty_id')
                ->whereNotNull('course_code')
                ->get(['id', 'faculty_id', 'course_code', 'year_level', 'program', 'major']);

            foreach ($rows as $row) {
                $candidates = DB::table('faculty_subjects')
                    ->where('faculty_id', $row->faculty_id)
                    ->whereRaw('UPPER(course_code) = ?', [strtoupper(trim((string) $row->course_code))])
                    ->get(['id', 'year_level', 'program', 'major']);

                $matching = $candidates->filter(function ($candidate) use ($row) {
                    if (trim((string) $candidate->year_level) !== trim((string) $row->year_level)) {
                        return false;
                    }
                    if (!empty($row->program) && trim((string) $candidate->program) !== trim((string) $row->program)) {
                        return false;
                    }
                    if (!empty($row->major) && trim((string) $candidate->major) !== trim((string) $row->major)) {
                        return false;
                    }
                    return true;
                });

                $match = $matching->count() === 1
                    ? $matching->first()
                    : ($candidates->count() === 1 ? $candidates->first() : null);

                if ($match) {
                    DB::table($table)->where('id', $row->id)->update([
                        'faculty_subject_id' => $match->id,
                    ]);
                }
            }
        }
    }

    private function dropCurriculumCourseColumn(string $table): void
    {
        $column = 'curriculum_course_id';
        if (!Schema::hasTable($table) || !Schema::hasColumn($table, $column)) {
            return;
        }

        $foreignKeys = DB::select(
            'SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ? AND REFERENCED_TABLE_NAME IS NOT NULL',
            [$table, $column]
        );
        foreach ($foreignKeys as $foreignKey) {
            $name = str_replace('`', '``', $foreignKey->CONSTRAINT_NAME);
            DB::statement("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$name}`");
        }

        $indexes = DB::select(
            'SELECT DISTINCT INDEX_NAME FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ? AND INDEX_NAME <> \'PRIMARY\'',
            [$table, $column]
        );
        foreach ($indexes as $index) {
            $name = str_replace('`', '``', $index->INDEX_NAME);
            DB::statement("ALTER TABLE `{$table}` DROP INDEX `{$name}`");
        }

        Schema::table($table, function (Blueprint $blueprint) use ($column) {
            $blueprint->dropColumn($column);
        });
    }

    public function down(): void
    {
        // The legacy tables are intentionally removed after their subject data
        // is copied. Recreating their former grid/document contents is not safe.
    }
};
