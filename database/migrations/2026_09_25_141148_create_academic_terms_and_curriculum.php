<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('academic_terms')) {
            Schema::create('academic_terms', function (Blueprint $table) {
                $table->id();
                $table->string('academic_year', 20);
                $table->string('semester', 30);
                $table->string('status', 20)->default('saved');
                $table->boolean('is_active')->default(false);
                $table->timestamps();
                $table->unique(['academic_year', 'semester']);
                $table->index(['academic_year', 'semester']);
                $table->index('is_active');
            });
        }

        $add = function (string $tableName, string $column, callable $definition): void {
            if (!Schema::hasColumn($tableName, $column)) {
                Schema::table($tableName, $definition);
            }
        };

        $add('schedules', 'semester', function (Blueprint $table) {
            $table->string('semester', 100)->nullable()->after('department_id');
        });
        $add('schedules', 'academic_year', function (Blueprint $table) {
            $table->string('academic_year', 100)->nullable()->after('semester');
        });
        $add('schedules', 'academic_term_id', function (Blueprint $table) {
            $table->unsignedBigInteger('academic_term_id')->nullable()->after('academic_year');
            $table->index('academic_term_id');
        });
        $add('schedules', 'curriculum_course_id', function (Blueprint $table) {
            $table->unsignedBigInteger('curriculum_course_id')->nullable()->after('course_code');
            $table->index('curriculum_course_id');
        });

        $add('schedule_drafts', 'semester', function (Blueprint $table) {
            $table->string('semester', 100)->nullable()->after('department_id');
        });
        $add('schedule_drafts', 'academic_year', function (Blueprint $table) {
            $table->string('academic_year', 100)->nullable()->after('semester');
        });
        $add('schedule_drafts', 'academic_term_id', function (Blueprint $table) {
            $table->unsignedBigInteger('academic_term_id')->nullable()->after('academic_year');
            $table->index('academic_term_id');
        });
        $add('schedule_drafts', 'curriculum_course_id', function (Blueprint $table) {
            $table->unsignedBigInteger('curriculum_course_id')->nullable()->after('course_code');
            $table->index('curriculum_course_id');
        });

        if (!Schema::hasTable('curricula')) {
            Schema::create('curricula', function (Blueprint $table) {
                $table->id();
                $table->foreignId('department_id')->constrained('departments')->cascadeOnDelete();
                $table->string('program', 100);
                $table->string('major', 100)->nullable();
                $table->string('curriculum_code', 100);
                $table->string('effective_year', 20);
                $table->string('status', 20)->default('active');
                $table->timestamps();
                $table->index(['department_id', 'program']);
            });
        }

        if (!Schema::hasTable('curriculum_courses')) {
            Schema::create('curriculum_courses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('curriculum_id')->constrained('curricula')->cascadeOnDelete();
                $table->string('course_code', 50);
                $table->string('subject', 255);
                $table->decimal('units', 5, 2)->default(0);
                $table->string('year_level', 50);
                $table->string('semester', 30);
                $table->string('subject_type', 50)->nullable();
                $table->string('prerequisite', 255)->nullable();
                $table->timestamps();
                $table->index(['curriculum_id', 'year_level', 'semester']);
                $table->unique(['curriculum_id', 'course_code', 'semester']);
            });
        }

        if (!Schema::hasColumn('faculty_subjects', 'curriculum_course_id')) {
            Schema::table('faculty_subjects', function (Blueprint $table) {
                $table->unsignedBigInteger('curriculum_course_id')->nullable()->after('faculty_id');
                $table->index('curriculum_course_id');
            });
        }

        // Foreign keys are added only after all referenced tables/columns exist.
        try {
            Schema::table('schedules', function (Blueprint $table) {
                $table->foreign('academic_term_id')->references('id')->on('academic_terms')->nullOnDelete();
                $table->foreign('curriculum_course_id')->references('id')->on('curriculum_courses')->nullOnDelete();
            });
        } catch (\Throwable $e) {
            // Ignore if a previous deployment already created one of the foreign keys.
        }

        try {
            Schema::table('schedule_drafts', function (Blueprint $table) {
                $table->foreign('academic_term_id')->references('id')->on('academic_terms')->nullOnDelete();
                $table->foreign('curriculum_course_id')->references('id')->on('curriculum_courses')->nullOnDelete();
            });
        } catch (\Throwable $e) {
        }

        try {
            Schema::table('faculty_subjects', function (Blueprint $table) {
                $table->foreign('curriculum_course_id')->references('id')->on('curriculum_courses')->nullOnDelete();
            });
        } catch (\Throwable $e) {
        }

        // Create terms from existing schedule/draft metadata so existing data remains visible.
        if (Schema::hasTable('academic_terms') && Schema::hasTable('schedules')) {
            $rows = DB::table('schedules')
                ->select('academic_year', 'semester')
                ->whereNotNull('academic_year')
                ->whereNotNull('semester')
                ->where('academic_year', '!=', '')
                ->where('semester', '!=', '')
                ->distinct()
                ->get();

            foreach ($rows as $row) {
                DB::table('academic_terms')->updateOrInsert(
                    [
                        'academic_year' => $row->academic_year,
                        'semester' => $row->semester,
                    ],
                    [
                        'status' => 'saved',
                        'is_active' => false,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        }

        if (Schema::hasTable('academic_terms') && Schema::hasTable('schedule_drafts')) {
            $rows = DB::table('schedule_drafts')
                ->select('academic_year', 'semester')
                ->whereNotNull('academic_year')
                ->whereNotNull('semester')
                ->where('academic_year', '!=', '')
                ->where('semester', '!=', '')
                ->distinct()
                ->get();

            foreach ($rows as $row) {
                DB::table('academic_terms')->updateOrInsert(
                    [
                        'academic_year' => $row->academic_year,
                        'semester' => $row->semester,
                    ],
                    [
                        'status' => 'saved',
                        'is_active' => false,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        }

        // Back-fill term IDs on old rows.
        if (Schema::hasTable('academic_terms')) {
            $terms = DB::table('academic_terms')->get();
            foreach ($terms as $term) {
                DB::table('schedules')
                    ->whereNull('academic_term_id')
                    ->where('academic_year', $term->academic_year)
                    ->where('semester', $term->semester)
                    ->update(['academic_term_id' => $term->id]);

                DB::table('schedule_drafts')
                    ->whereNull('academic_term_id')
                    ->where('academic_year', $term->academic_year)
                    ->where('semester', $term->semester)
                    ->update(['academic_term_id' => $term->id]);
            }

            $hasActive = DB::table('academic_terms')->where('is_active', true)->exists();
            if (!$hasActive) {
                $term = DB::table('academic_terms')->orderByDesc('id')->first();
                if (!$term) {
                    $year = (int) now()->format('Y');
                    $id = DB::table('academic_terms')->insertGetId([
                        'academic_year' => $year . '-' . ($year + 1),
                        'semester' => '1st Semester',
                        'status' => 'active',
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    DB::table('academic_terms')->where('id', $id)->update(['is_active' => true, 'status' => 'active']);
                } else {
                    DB::table('academic_terms')->where('id', $term->id)->update(['is_active' => true, 'status' => 'active']);
                }
            }
        }
    }

    public function down(): void
    {
        // Intentionally conservative: this migration manages historical academic data.
        // Do not drop the data automatically on rollback.
    }
};