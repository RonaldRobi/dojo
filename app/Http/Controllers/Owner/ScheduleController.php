<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\Instructor;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $dojoId = currentDojo();
        
        // Views will handle displaying schedules
        return view('owner.schedules.index');
    }

    public function create()
    {
        $dojoId = currentDojo();
        
        // Get instructors for this dojo
        $instructors = Instructor::where('dojo_id', $dojoId)
            ->with('user')
            ->get();
        
        return view('owner.schedules.create', compact('instructors'));
    }

    public function store(Request $request)
    {
        $dojoId = currentDojo();
        
        $validated = $request->validate([
            'day_of_week' => 'required|integer|between:0,6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'instructor_id' => 'nullable|exists:instructors,id',
            'is_active' => 'nullable|boolean',
        ]);

        // Auto-add dojo_id
        $validated['dojo_id'] = $dojoId;
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        ClassSchedule::create($validated);

        return redirect()->route('owner.schedules.index')
            ->with('success', 'Schedule created successfully.');
    }

    public function show(ClassSchedule $schedule)
    {
        // Ensure schedule belongs to owner's dojo
        if ($schedule->dojo_id !== currentDojo()) {
            abort(403, 'Unauthorized access.');
        }
        
        $schedule->load(['dojo', 'instructor.user']);
        return view('owner.schedules.show', compact('schedule'));
    }

    public function edit(ClassSchedule $schedule)
    {
        // Ensure schedule belongs to owner's dojo
        if ($schedule->dojo_id !== currentDojo()) {
            abort(403, 'Unauthorized access.');
        }
        
        $dojoId = currentDojo();
        
        // Get instructors for this dojo
        $instructors = Instructor::where('dojo_id', $dojoId)
            ->with('user')
            ->get();
        
        return view('owner.schedules.edit', compact('schedule', 'instructors'));
    }

    public function update(Request $request, ClassSchedule $schedule)
    {
        // Ensure schedule belongs to owner's dojo
        if ($schedule->dojo_id !== currentDojo()) {
            abort(403, 'Unauthorized access.');
        }
        
        $validated = $request->validate([
            'day_of_week' => 'required|integer|between:0,6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'instructor_id' => 'nullable|exists:instructors,id',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $schedule->update($validated);

        return redirect()->route('owner.schedules.index')
            ->with('success', 'Schedule updated successfully.');
    }

    public function destroy(ClassSchedule $schedule)
    {
        // Ensure schedule belongs to owner's dojo
        if ($schedule->dojo_id !== currentDojo()) {
            abort(403, 'Unauthorized access.');
        }
        
        $schedule->delete();
        
        return redirect()->route('owner.schedules.index')
            ->with('success', 'Schedule deleted successfully.');
    }
}
