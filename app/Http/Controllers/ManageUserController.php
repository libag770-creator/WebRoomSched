<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Department;

class ManageUserController extends Controller
{

public function showResetPassword($id)
{
    $user = User::findOrFail($id);

    return view('admin.resetpassword', compact('user'));
}

public function resetPassword(Request $request, $id)
{
    $request->validate([
        'password' => 'required|min:6|confirmed',
    ]);

    $user = User::findOrFail($id);

    $user->password = Hash::make($request->password);

    $user->save();

    return redirect()->route('admin.manageusers')
        ->with('success', 'Password reset successfully.');
}

public function destroy($id)
{
    $user = User::findOrFail($id);

    $user->delete();

    return redirect()->route('admin.manageusers')
        ->with('success', 'User deleted successfully.');
}


   public function edit($id)
{
    $user = User::findOrFail($id);

    $departments = Department::orderBy('name')->get();

    return view(
        'admin.edituser',
        compact(
            'user',
            'departments'
        )
    );
}

public function update(
    Request $request,
    $id
) {
    $user = User::findOrFail($id);


    $request->validate([

        'name' =>
            'required|string|max:255',

        'username' =>
            'required|string|max:255|unique:users,username,' . $id,

        'email' =>
            'required|email|max:255|unique:users,email,' . $id,

        'role' =>
            'required|in:admin,chair,faculty',

        'department_id' =>
            'nullable|exists:departments,id',

    ]);


    /*
    |--------------------------------------------------------------------------
    | Chair / Faculty must have department
    |--------------------------------------------------------------------------
    */

    if (
        in_array($request->role, ['chair', 'faculty'])
        && !$request->department_id
    ) {

        return back()
            ->withErrors([
                'department_id' =>
                    'Please select a department for this user.'
            ])
            ->withInput();
    }


    /*
    |--------------------------------------------------------------------------
    | Admin doesn't need department
    |--------------------------------------------------------------------------
    */

    $departmentId =
        $request->role === 'admin'
            ? null
            : $request->department_id;


    $user->update([

        'name' =>
            $request->name,

        'username' =>
            $request->username,

        'email' =>
            $request->email,

        'role' =>
            $request->role,

        'department_id' =>
            $departmentId,

    ]);


    return redirect()
        ->route('admin.manageusers')
        ->with(
            'success',
            'User updated successfully.'
        );
}

 public function index()
{
    $users = User::with('department')
        ->orderBy('name')
        ->get();

    return view(
        'admin.manageusers',
        compact('users')
    );
}

    public function create()
{
    $departments = Department::orderBy('name')->get();

    return view(
        'admin.adduser',
        compact('departments')
    );
}

   public function store(Request $request)
{
    $request->validate([

        'name' =>
            'required|string|max:255',

        'username' =>
            'required|string|max:255|unique:users,username',

        'email' =>
            'required|email|max:255|unique:users,email',

        'password' =>
            'required|min:6',

        'role' =>
            'required|in:admin,chair,faculty',

        'department_id' =>
            'nullable|exists:departments,id',

    ]);


    /*
    |--------------------------------------------------------------------------
    | Department required for Chair and Faculty
    |--------------------------------------------------------------------------
    */

    if (
        in_array($request->role, ['chair', 'faculty'])
        && !$request->department_id
    ) {

        return back()
            ->withErrors([
                'department_id' =>
                    'Please select a department for this user.'
            ])
            ->withInput();
    }


    /*
    |--------------------------------------------------------------------------
    | Admin does not need department
    |--------------------------------------------------------------------------
    */

    $departmentId =
        $request->role === 'admin'
            ? null
            : $request->department_id;


    User::create([

        'name' =>
            $request->name,

        'username' =>
            $request->username,

        'email' =>
            $request->email,

        'password' =>
            Hash::make($request->password),

        'role' =>
            $request->role,

        'department_id' =>
            $departmentId,

    ]);


    return redirect()
        ->route('admin.manageusers')
        ->with(
            'success',
            'User added successfully.'
        );
}
}