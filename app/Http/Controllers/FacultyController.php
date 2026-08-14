<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FacultyController extends Controller
{
    public function dashboard()
{
    $reservations = Reservation::with('room')
        ->where('user_id', Auth::id())
        ->latest()
        ->get();

    return view('faculty.dashboard', compact('reservations'));
}

    public function reserveRoom(Request $request, Room $room)
    {
        $request->validate([
            'date' => 'required|date',
            'time' => 'required|string',
            'purpose' => 'required|string|max:255',
        ]);

        Reservation::create([
            'room_id' => $room->id,
            'user_id' => auth()->id(),
            'date' => $request->date,
            'day' => strtoupper(date('D', strtotime($request->date))),
            'time' => $request->time,
            'purpose' => $request->purpose,
            'status' => 'Pending',
        ]);

        return redirect()
            ->route('faculty.vacant')
            ->with('success', 'Room reservation submitted. Waiting for Chair approval.');
    }
}
