<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\DojoClass;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $dojoId = currentDojo();
        
        $query = ClassSchedule::whereHas('dojoClass', function($q) use ($dojoId) {
            $q->where('dojo_id', $dojoId);
        })->with(['dojoClass', 'instructor']);

        if ($request->has('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->has('day_of_week')) {
            $query->where('day_of_week', $request->day_of_week);
        }

        $schedules = $query->get();

        return response()->json($schedules);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'instructor_id' => 'nullable|exists:instructors,id',
            'day_of_week' => 'required|integer|between:0,6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $schedule = ClassSchedule::create($validated);

        return response()->json($schedule, 201);
    }

    public function show(ClassSchedule $classSchedule)
    {
        $classSchedule->load(['dojoClass', 'instructor', 'enrollments.member']);
        return response()->json($classSchedule);
    }

    public function update(Request $request, ClassSchedule $classSchedule)
    {
        $validated = $request->validate([
            'instructor_id' => 'nullable|exists:instructors,id',
            'day_of_week' => 'sometimes|integer|between:0,6',
            'start_time' => 'sometimes|date_format:H:i',
            'end_time' => 'sometimes|date_format:H:i|after:start_time',
            'location' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $classSchedule->update($validated);

        return response()->json($classSchedule);
    }

    public function destroy(ClassSchedule $classSchedule)
    {
        $classSchedule->delete();
        return response()->json(['message' => 'Schedule deleted successfully']);
    }
}
