<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;

class FacultyLoginController extends Controller
{
    public function showLogin()
    {
        return view('faculty.login');
    }

    public function login(Request $request)
{
    $request->validate([
        'username' => 'required',
        'password' => 'required',
        'role' => 'required'
    ]);

    $credentials = [
        'username' => $request->username,
        'password' => $request->password,
    ];

    if (Auth::attempt($credentials)) {

        $user = Auth::user();

        // Check if selected role matches account role
        if ($user->role !== $request->role) {

            Auth::logout();

            return back()
                ->withInput()
                ->with('error', 'Wrong role selected for this account.');
        }

        $request->session()->regenerate();

        // Redirect according to role

        if ($user->role === 'faculty') {

            return redirect()->route('faculty.dashboard');

        }

        if ($user->role === 'chair') {

            return redirect()->route('chair.dashboard');

        }

        if ($user->role === 'admin') {

            return redirect()->route('admin.dashboard');

        }

        Auth::logout();

        return back()
            ->with('error', 'Invalid account role.');
    }

    return back()
        ->withInput()
        ->with('error', 'Invalid username or password.');
}

    public function dashboard()
    {
        $reservations = Reservation::with('room')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('faculty.dashboard', compact('reservations'));
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('faculty.login');
    }
}