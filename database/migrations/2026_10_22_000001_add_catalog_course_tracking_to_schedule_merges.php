<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $indexes = collect(DB::select('SHOW INDEX FROM schedule_merges'));
        if (!$indexes->contains(fn ($index) => $index->Key_name === 'schedule_merges_schedule_id_index')) {
            // Keep the schedule foreign key indexed while replacing its old
            // composite uniqueness rule with the course-specific rule below.
            Schema::table('schedule_merges', function (Blueprint $table) {
                $table->index('schedule_id', 'schedule_merges_schedule_id_index');
            });
        }

        if ($indexes->contains(fn ($index) => $index->Key_name === 'schedule_merges_schedule_id_department_id_unique')) {
            Schema::table('schedule_merges', function (Blueprint $table) {
                $table->dropUnique('schedule_merges_schedule_id_department_id_unique');
            });
        }

        if (!Schema::hasColumn('schedule_merges', 'subject_id')) {
            Schema::table('schedule_merges', function (Blueprint $table) {
                $table->foreignId('subject_id')
                    ->nullable()
                    ->after('faculty_subject_id')
                    ->constrained('subjects')
                    ->nullOnDelete();
            });
        }

        if (!Schema::hasColumn('schedule_merges', 'requested_by')) {
            Schema::table('schedule_merges', function (Blueprint $table) {
                $table->foreignId('requested_by')
                    ->nullable()
                    ->after('department_id')
                    ->constrained('users')
                    ->nullOnDelete();
            });
        }

        if (!Schema::hasColumn('schedule_merges', 'request_group_id')) {
            Schema::table('schedule_merges', function (Blueprint $table) {
                $table->uuid('request_group_id')->nullable()->after('subject_id');
                $table->index('request_group_id', 'schedule_merges_request_group_index');
            });
        }

        if (!Schema::hasColumn('schedule_merges', 'decision_note')) {
            Schema::table('schedule_merges', function (Blueprint $table) {
                $table->text('decision_note')->nullable()->after('approved_at');
            });
        }

        Schema::table('schedule_merges', function (Blueprint $table) {
            $table->foreignId('faculty_subject_id')
                ->nullable()
                ->change();
        });

        DB::table('schedule_merges as merges')
            ->join('faculty_subjects as assignments', 'assignments.id', '=', 'merges.faculty_subject_id')
            ->whereNull('merges.subject_id')
            ->whereNotNull('assignments.subject_id')
            ->update(['merges.subject_id' => DB::raw('assignments.subject_id')]);

        $legacyRows = DB::table('schedule_merges as merges')
            ->join('schedules', 'schedules.id', '=', 'merges.schedule_id')
            ->whereNull('merges.request_group_id')
            ->select([
                'merges.id',
                'merges.department_id',
                'merges.faculty_subject_id',
                'schedules.academic_term_id',
                'schedules.room_id',
                'schedules.faculty_id',
                'schedules.course_code',
                'schedules.subject',
                'schedules.year_level',
                'schedules.program',
                'schedules.major',
                'schedules.semester',
                'schedules.academic_year',
            ])
            ->get();

        foreach ($legacyRows->groupBy(function ($row) {
            return json_encode([
                $row->department_id,
                $row->faculty_subject_id,
                $row->academic_term_id,
                $row->room_id,
                $row->faculty_id,
                trim((string) $row->course_code),
                trim((string) $row->subject),
                trim((string) $row->year_level),
                trim((string) $row->program),
                trim((string) $row->major),
                trim((string) $row->semester),
                trim((string) $row->academic_year),
            ]);
        }) as $classRows) {
            $groupId = (string) Str::uuid();
            DB::table('schedule_merges')
                ->whereIn('id', $classRows->pluck('id'))
                ->whereNull('request_group_id')
                ->update(['request_group_id' => $groupId]);
        }

        $indexes = collect(DB::select('SHOW INDEX FROM schedule_merges'));
        if (!$indexes->contains(fn ($index) => $index->Key_name === 'schedule_merges_schedule_subject_department_unique')) {
            Schema::table('schedule_merges', function (Blueprint $table) {
                $table->unique(
                    ['schedule_id', 'subject_id', 'department_id'],
                    'schedule_merges_schedule_subject_department_unique'
                );
            });
        }
    }

    public function down(): void
    {
        // Requests created with catalog subject IDs cannot be converted back
        // to faculty assignments safely. Keep their records and new columns.
    }
};
