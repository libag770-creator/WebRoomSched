<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $assignments = DB::table('faculty_subjects as assignments')
            ->join('subjects', 'subjects.id', '=', 'assignments.subject_id')
            ->join('programs', 'programs.id', '=', 'subjects.program_id')
            ->whereNotNull('assignments.subject_id')
            ->select([
                'assignments.id as faculty_subject_id',
                'assignments.faculty_id',
                'assignments.course_code',
                'assignments.subject',
                'assignments.subject_id',
                'programs.department_id',
            ])
            ->orderBy('assignments.id')
            ->get()
            ->groupBy(fn ($row) => implode('|', [
                (int) $row->faculty_id,
                (int) $row->department_id,
                mb_strtoupper(trim((string) $row->course_code)),
                mb_strtolower(trim((string) $row->subject)),
            ]));

        foreach ($assignments as $group) {
            if ($group->count() < 2) continue;
            foreach ($group as $assignment) {
                foreach ($group as $cohort) {
                    if ((int) $cohort->subject_id === (int) $assignment->subject_id) continue;
                    DB::table('faculty_subject_cohorts')->insertOrIgnore([
                        'faculty_subject_id' => $assignment->faculty_subject_id,
                        'subject_id' => $cohort->subject_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        // Keep the associations: users may have added new groups after migration.
    }
};
