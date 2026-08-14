<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;

class RoomReassignmentController extends Controller
{
    public function index()
    {
        $departments = Department::all();

        return view('admin.roomreassignment', compact('departments'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'room_id' => 'required',
            'new_department_id' => 'required',
            'new_building_id' => 'required',
            'new_room_id' => 'required',
        ]);

        // Room reassignment logic will go here.

        return redirect()
            ->route('admin.roomreassignment')
            ->with('success', 'Room successfully reassigned.');
    }
}