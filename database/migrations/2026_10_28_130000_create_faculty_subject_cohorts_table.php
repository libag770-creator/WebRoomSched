<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faculty_subject_cohorts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faculty_subject_id')->constrained('faculty_subjects')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['faculty_subject_id', 'subject_id'], 'faculty_subject_cohorts_unique');
            $table->index('subject_id', 'faculty_subject_cohorts_subject_index');
        });

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
        Schema::dropIfExists('faculty_subject_cohorts');
    }
};
