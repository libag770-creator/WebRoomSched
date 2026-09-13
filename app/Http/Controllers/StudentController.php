<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Schedule;

class StudentController extends Controller
{
    /**
     * Student View Schedule
     */
    public function schedule()
    {
        // Get all departments that exist in the database
        $departments = Department::orderBy('name', 'asc')->get();

        // Get semester and academic year from schedules
        $latestSchedule = Schedule::latest('id')->first();

        $semester = $latestSchedule?->semester ?? 'First Semester';
        $academicYear = $latestSchedule?->academic_year ?? date('Y') . ' - ' . (date('Y') + 1);

        return view('student.schedule', compact(
            'departments',
            'semester',
            'academicYear'
        ));
    }

    /**
     * Get schedules according to department and year level.
     */
    public function getSchedules(Request $request)
    {
        $request->validate([
            'department_id' => 'required|integer',
            'year_level' => 'required|string',
        ]);

        $schedules = Schedule::where('department_id', $request->department_id)
            ->where('year_level', $request->year_level)
            ->orderBy('day', 'asc')
            ->orderBy('time', 'asc')
            ->get([
                'id',
                'room_id',
                'faculty_id',
                'department_id',
                'semester',
                'academic_year',
                'day',
                'time',
                'course_code',
                'subject',
                'year_level',
                'major',
                'instructor',
                'subject_type',
                'description',
                'color',
            ]);

        return response()->json([
            'success' => true,
            'schedules' => $schedules,
        ]);
    }
}