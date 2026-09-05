<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;

class FacultyLoginController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
{
    $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
    ]);


    /*
    | Attempt Login
    */

    $credentials = [
        'username' => $request->username,
        'password' => $request->password,
    ];


    if (!Auth::attempt($credentials)) {

        return back()
            ->withInput($request->only('username'))
            ->with(
                'error',
                'Invalid username or password.'
            );
    }


    /*
    | Regenerate Session
    */

    $request->session()->regenerate();


    /*
    | Get User Role From Database
    */

    $user = Auth::user();


    /*
    | ADMIN
    */

    if ($user->role === 'admin') {

        return redirect()
            ->route('admin.dashboard');
    }


    /*
    | DEPARTMENT CHAIR
    */

    if ($user->role === 'chair') {

        return redirect()
            ->route('chair.dashboard');
    }


    /*
    | FACULTY
    */

    if ($user->role === 'faculty') {

        return redirect()
            ->route('faculty.dashboard');
    }


    /*
    | Invalid / Missing Role
    */

    Auth::logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect()
        ->route('login')
        ->with(
            'error',
            'Your account does not have a valid role. Please contact the administrator.'
        );
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

        return redirect()->route('login');
    }
}