<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ManageUserController extends Controller
{
    public function index()
    {
        $users = User::with('department')->orderBy('name')->get();

        $archivedUsers = User::onlyTrashed()
            ->with('department')
            ->orderBy('name')
            ->get();

        $departments = Department::orderBy('name')->get();

        return view('admin.manageusers', compact(
            'users',
            'archivedUsers',
            'departments'
        ));
    }

    public function create()
    {
        // Legacy route only. The combined page contains its own Add User modal.
        return redirect()->route('admin.manageusers');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', Rule::in(['admin', 'chair', 'faculty'])],
            'department_id' => ['nullable', 'exists:departments,id'],
        ]);

        if (in_array($validated['role'], ['chair', 'faculty'], true)
            && empty($validated['department_id'])) {
            return back()->withErrors([
                'department_id' => 'Please select a department for this user.',
            ])->withInput()->with('open_modal', 'add');
        }

        User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'department_id' => $validated['role'] === 'admin'
                ? null
                : ($validated['department_id'] ?? null),
            'status' => 'active',
        ]);

        return redirect()->route('admin.manageusers')
            ->with('success', 'User added successfully.');
    }

    public function edit($id)
    {
        return redirect()->route('admin.manageusers')
            ->with('info', 'Use the Edit button on the Manage Users page.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required', 'string', 'max:255',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'role' => ['required', Rule::in(['admin', 'chair', 'faculty'])],
            'department_id' => ['nullable', 'exists:departments,id'],
            'status' => ['nullable', Rule::in(['active', 'inactive'])],
        ]);

        if (in_array($validated['role'], ['chair', 'faculty'], true)
            && empty($validated['department_id'])) {
            return back()->withErrors([
                'department_id' => 'Please select a department for this user.',
            ])->withInput()->with('open_modal', 'edit-' . $user->id);
        }

        $user->update([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'department_id' => $validated['role'] === 'admin'
                ? null
                : ($validated['department_id'] ?? null),
            'status' => $validated['status'] ?? $user->status ?? 'active',
        ]);

        return redirect()->route('admin.manageusers')
            ->with('success', 'User updated successfully.');
    }

    public function showResetPassword($id)
    {
        return redirect()->route('admin.manageusers')
            ->with('open_modal', 'reset-' . $id);
    }

    public function resetPassword(Request $request, $id)
    {
        $validated = $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::findOrFail($id);
        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->route('admin.manageusers')
            ->with('success', 'Password reset successfully.');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->status = ($user->status ?? 'active') === 'active'
            ? 'inactive'
            : 'active';
        $user->save();

        return redirect()->route('admin.manageusers')->with(
            'success',
            $user->status === 'active'
                ? 'User account activated successfully.'
                : 'User account deactivated successfully.'
        );
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Soft-delete the user account only. Do not delete related buildings,
        // rooms, schedules, or reservations from this controller.
        $user->delete();

        return redirect()->route('admin.manageusers')
            ->with('success', 'User archived. Related records were not deleted.');
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('admin.manageusers')
            ->with('success', 'User restored successfully.');
    }
}
