<?php

namespace App\Http\Middleware;

use App\Models\Department;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireStudentAccessCode
{
    public function handle(Request $request, Closure $next): Response
    {
        $department = Department::find($request->session()->get('student_access_department_id'));
        $sessionHash = (string) $request->session()->get('student_access_code_hash', '');

        if (!$department || !$department->student_access_code_hash || !$sessionHash || !hash_equals($department->student_access_code_hash, $sessionHash)) {
            $request->session()->forget(['student_access_department_id', 'student_access_code_hash']);
            return redirect()->route('student.access');
        }

        return $next($request);
    }
}
