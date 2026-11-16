<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAccessController extends Controller
{
    private function codeHash(string $code): string
    {
        return hash_hmac('sha256', strtoupper(preg_replace('/[\s-]+/', '', $code)), (string) config('app.key'));
    }

    public function show(Request $request)
    {
        if ($this->validSessionDepartment($request)) {
            return redirect()->route('student.schedule');
        }

        return view('student.access');
    }

    public function verify(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:32', 'regex:/^[A-HJ-NP-Z2-9]{4}-?[A-HJ-NP-Z2-9]{4}$/i'],
        ]);
        $department = Department::query()->where('student_access_code_hash', $this->codeHash($validated['code']))->first();

        if (!$department) {
            return back()->withErrors(['code' => 'That code could not be verified. Check it with your instructor and try again.'])->withInput();
        }

        $request->session()->regenerate();
        $request->session()->put([
            'student_access_department_id' => $department->id,
            'student_access_code_hash' => $department->student_access_code_hash,
        ]);

        return redirect()->route('student.schedule');
    }

    public function saveForChair(Request $request)
    {
        $user = Auth::user();
        abort_unless($user && $user->role === 'chair' && $user->department_id, 403);

        $validated = $request->validate([
            'code' => ['required', 'string', 'max:9', 'regex:/^[A-HJ-NP-Z2-9]{4}-[A-HJ-NP-Z2-9]{4}$/i'],
        ]);
        $code = strtoupper($validated['code']);
        $hash = $this->codeHash($code);
        $department = Department::findOrFail($user->department_id);
        $collision = Department::query()->where('student_access_code_hash', $hash)->where('id', '!=', $department->id)->exists();

        if ($collision) {
            return back()->with('error', 'That code is already used by another department. Generate a new code.');
        }

        $department->student_access_code = $code;
        $department->student_access_code_hash = $hash;
        $department->save();

        return back()->with('success', 'Student access code saved. Share it with instructors so they can pass it to students.');
    }

    private function validSessionDepartment(Request $request): ?Department
    {
        $department = Department::find($request->session()->get('student_access_department_id'));
        $sessionHash = (string) $request->session()->get('student_access_code_hash', '');

        if (!$department || !$department->student_access_code_hash || !$sessionHash || !hash_equals($department->student_access_code_hash, $sessionHash)) {
            $request->session()->forget(['student_access_department_id', 'student_access_code_hash']);
            return null;
        }

        return $department;
    }

    public function exit(Request $request)
    {
        $request->session()->forget(['student_access_department_id', 'student_access_code_hash']);
        $request->session()->regenerateToken();
        return redirect()->route('student.access');
    }
}
