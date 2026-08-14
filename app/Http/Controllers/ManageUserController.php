<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

    return view('admin.edituser', compact('user'));
}

public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'name' => 'required',
        'username' => 'required|unique:users,username,' . $id,
        'email' => 'required|email|unique:users,email,' . $id,
        'role' => 'required',
    ]);

    $user->update([
        'name' => $request->name,
        'username' => $request->username,
        'email' => $request->email,
        'role' => $request->role,
    ]);

    return redirect()->route('admin.manageusers')
        ->with('success', 'User updated successfully.');
}

    public function index()
    {
        $users = User::all();

        return view('admin.manageusers', compact('users'));
    }

    public function create()
    {
        return view('admin.adduser');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.manageusers')
            ->with('success', 'User added successfully.');
    }
}