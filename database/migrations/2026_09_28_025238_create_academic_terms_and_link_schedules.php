<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | CREATE ACADEMIC TERMS TABLE
        |--------------------------------------------------------------------------
        */

        if (!Schema::hasTable('academic_terms')) {

            Schema::create('academic_terms', function (Blueprint $table) {

                $table->id();

                $table->string(
                    'academic_year',
                    20
                );

                $table->string(
                    'semester',
                    30
                );

                $table->string(
                    'status',
                    20
                )->default('saved');

                $table->boolean(
                    'is_active'
                )->default(false);

                $table->timestamps();

                $table->unique([
                    'academic_year',
                    'semester'
                ]);
            });
        }


        /*
        |--------------------------------------------------------------------------
        | ADD ACADEMIC TERM ID TO SCHEDULES
        |--------------------------------------------------------------------------
        */

        if (
            Schema::hasTable('schedules') &&
            !Schema::hasColumn(
                'schedules',
                'academic_term_id'
            )
        ) {

            Schema::table('schedules', function (Blueprint $table) {

                $table->foreignId(
                    'academic_term_id'
                )
                ->nullable()
                ->after('department_id')
                ->constrained(
                    'academic_terms'
                )
                ->nullOnDelete();

            });
        }


        /*
        |--------------------------------------------------------------------------
        | ADD ACADEMIC TERM ID TO SCHEDULE DRAFTS
        |--------------------------------------------------------------------------
        */

        if (
            Schema::hasTable('schedule_drafts') &&
            !Schema::hasColumn(
                'schedule_drafts',
                'academic_term_id'
            )
        ) {

            Schema::table('schedule_drafts', function (Blueprint $table) {

                $table->foreignId(
                    'academic_term_id'
                )
                ->nullable()
                ->after('department_id')
                ->constrained(
                    'academic_terms'
                )
                ->nullOnDelete();

            });
        }


        /*
        |--------------------------------------------------------------------------
        | CREATE TERMS FROM EXISTING SCHEDULES
        |--------------------------------------------------------------------------
        |
        | This prevents your existing schedules from becoming unusable.
        |
        */

        if (
            Schema::hasTable('schedules') &&
            Schema::hasColumn('schedules', 'semester') &&
            Schema::hasColumn('schedules', 'academic_year')
        ) {

            $existingSchedules = DB::table('schedules')
                ->select(
                    'semester',
                    'academic_year'
                )
                ->whereNotNull('semester')
                ->whereNotNull('academic_year')
                ->where('semester', '<>', '')
                ->where('academic_year', '<>', '')
                ->distinct()
                ->get();


            foreach ($existingSchedules as $schedule) {

                DB::table('academic_terms')->insertOrIgnore([

                    'academic_year' =>
                        trim(
                            (string) $schedule->academic_year
                        ),

                    'semester' =>
                        trim(
                            (string) $schedule->semester
                        ),

                    'status' =>
                        'saved',

                    'is_active' =>
                        false,

                    'created_at' =>
                        now(),

                    'updated_at' =>
                        now(),
                ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | CREATE TERMS FROM EXISTING DRAFTS
        |--------------------------------------------------------------------------
        |
        */

        if (
            Schema::hasTable('schedule_drafts') &&
            Schema::hasColumn('schedule_drafts', 'semester') &&
            Schema::hasColumn('schedule_drafts', 'academic_year')
        ) {

            $existingDrafts = DB::table('schedule_drafts')
                ->select(
                    'semester',
                    'academic_year'
                )
                ->whereNotNull('semester')
                ->whereNotNull('academic_year')
                ->where('semester', '<>', '')
                ->where('academic_year', '<>', '')
                ->distinct()
                ->get();


            foreach ($existingDrafts as $draft) {

                DB::table('academic_terms')->insertOrIgnore([

                    'academic_year' =>
                        trim(
                            (string) $draft->academic_year
                        ),

                    'semester' =>
                        trim(
                            (string) $draft->semester
                        ),

                    'status' =>
                        'saved',

                    'is_active' =>
                        false,

                    'created_at' =>
                        now(),

                    'updated_at' =>
                        now(),
                ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | LINK OLD SCHEDULES TO THEIR ACADEMIC TERMS
        |--------------------------------------------------------------------------
        */

        if (
            Schema::hasTable('schedules') &&
            Schema::hasColumn('schedules', 'academic_term_id') &&
            Schema::hasColumn('schedules', 'semester') &&
            Schema::hasColumn('schedules', 'academic_year')
        ) {

            $terms = DB::table('academic_terms')
                ->get();


            foreach ($terms as $term) {

                DB::table('schedules')
                    ->whereNull(
                        'academic_term_id'
                    )
                    ->where(
                        'semester',
                        $term->semester
                    )
                    ->where(
                        'academic_year',
                        $term->academic_year
                    )
                    ->update([
                        'academic_term_id' =>
                            $term->id,

                        'updated_at' =>
                            now(),
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | LINK OLD DRAFTS TO THEIR ACADEMIC TERMS
        |--------------------------------------------------------------------------
        */

        if (
            Schema::hasTable('schedule_drafts') &&
            Schema::hasColumn('schedule_drafts', 'academic_term_id') &&
            Schema::hasColumn('schedule_drafts', 'semester') &&
            Schema::hasColumn('schedule_drafts', 'academic_year')
        ) {

            $terms = DB::table('academic_terms')
                ->get();


            foreach ($terms as $term) {

                DB::table('schedule_drafts')
                    ->whereNull(
                        'academic_term_id'
                    )
                    ->where(
                        'semester',
                        $term->semester
                    )
                    ->where(
                        'academic_year',
                        $term->academic_year
                    )
                    ->update([
                        'academic_term_id' =>
                            $term->id,

                        'updated_at' =>
                            now(),
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | MAKE ONE TERM ACTIVE
        |--------------------------------------------------------------------------
        |
        | If terms already exist, use the newest one.
        |
        */

        $hasActiveTerm = DB::table('academic_terms')
            ->where(
                'is_active',
                true
            )
            ->exists();


        if (!$hasActiveTerm) {

            $latestTerm = DB::table('academic_terms')
                ->orderByDesc('id')
                ->first();


            if ($latestTerm) {

                DB::table('academic_terms')
                    ->where(
                        'id',
                        $latestTerm->id
                    )
                    ->update([

                        'is_active' =>
                            true,

                        'status' =>
                            'active',

                        'updated_at' =>
                            now(),
                    ]);
            }
        }
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | REMOVE ACADEMIC TERM ID FROM SCHEDULE DRAFTS
        |--------------------------------------------------------------------------
        */

        if (
            Schema::hasTable('schedule_drafts') &&
            Schema::hasColumn(
                'schedule_drafts',
                'academic_term_id'
            )
        ) {

            Schema::table(
                'schedule_drafts',
                function (Blueprint $table) {

                    $table->dropForeign([
                        'academic_term_id'
                    ]);

                    $table->dropColumn(
                        'academic_term_id'
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | REMOVE ACADEMIC TERM ID FROM SCHEDULES
        |--------------------------------------------------------------------------
        */

        if (
            Schema::hasTable('schedules') &&
            Schema::hasColumn(
                'schedules',
                'academic_term_id'
            )
        ) {

            Schema::table(
                'schedules',
                function (Blueprint $table) {

                    $table->dropForeign([
                        'academic_term_id'
                    ]);

                    $table->dropColumn(
                        'academic_term_id'
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | DROP ACADEMIC TERMS TABLE
        |--------------------------------------------------------------------------
        */

        Schema::dropIfExists(
            'academic_terms'
        );
    }
};