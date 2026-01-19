<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DojoClass;
use App\Models\ClassSchedule;
use App\Models\Dojo;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ClassManagementController extends Controller
{
    // All Classes - View all classes across all dojos
    public function templates(Request $request)
    {
        $query = DojoClass::with(['dojo', 'schedules']);

        if ($request->has('dojo_id')) {
            $query->where('dojo_id', $request->dojo_id);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $classes = $query->orderBy('name')->paginate(20);
        $dojos = Dojo::all();

        return view('admin.classes.templates', compact('classes', 'dojos'));
    }

    // Show single class details
    public function show($id)
    {
        $class = DojoClass::with(['dojo', 'schedules.instructor', 'enrollments.member'])->findOrFail($id);
        return view('admin.classes.show', compact('class'));
    }

    // Create Class Form
    public function create()
    {
        $dojos = Dojo::all();
        return view('admin.classes.create', compact('dojos'));
    }

    // Store New Class
    public function store(Request $request)
    {
        $validated = $request->validate([
            'dojo_id' => 'required|exists:dojos,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'max_capacity' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        DojoClass::create($validated);

        return redirect()->route('admin.classes.templates')
            ->with('success', 'Class created successfully!');
    }

    // Edit Class Form
    public function edit($id)
    {
        $class = DojoClass::findOrFail($id);
        $dojos = Dojo::all();
        return view('admin.classes.edit', compact('class', 'dojos'));
    }

    // Update Class
    public function update(Request $request, $id)
    {
        $class = DojoClass::findOrFail($id);

        $validated = $request->validate([
            'dojo_id' => 'required|exists:dojos,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'max_capacity' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $class->update($validated);

        return redirect()->route('admin.classes.templates')
            ->with('success', 'Class updated successfully!');
    }

    // Delete Class
    public function destroy($id)
    {
        $class = DojoClass::findOrFail($id);
        $class->delete();

        return redirect()->route('admin.classes.templates')
            ->with('success', 'Class deleted successfully!');
    }

    // Standar Kapasitas Kelas
    public function capacityStandards(Request $request)
    {
        // Get capacity statistics per class
        $classes = DojoClass::with(['dojo', 'enrollments'])
            ->get()
            ->map(function($class) {
                return [
                    'id' => $class->id,
                    'name' => $class->name,
                    'dojo' => $class->dojo->name,
                    'max_capacity' => $class->max_capacity ?? 0,
                    'current_enrollment' => $class->enrollments->count(),
                    'utilization' => $class->max_capacity > 0 ? round(($class->enrollments->count() / $class->max_capacity) * 100, 2) : 0,
                ];
            })
            ->sortByDesc('utilization')
            ->values();

        $dojos = Dojo::all();

        return view('admin.classes.capacity-standards', compact('classes', 'dojos'));
    }

    // Create Schedule Form - Simple weekly schedule
    public function monitoring(Request $request)
    {
        $dojos = Dojo::all();
        $instructors = \App\Models\Instructor::with('user')->get();
        
        return view('admin.classes.monitoring', compact('dojos', 'instructors'));
    }

    // Store New Schedule
    public function storeSchedule(Request $request)
    {
        $validated = $request->validate([
            'dojo_id' => 'required|exists:dojos,id',
            'instructor_id' => 'nullable|exists:instructors,id',
            'day_of_week' => 'required|integer|between:0,6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : true;
        $validated['class_id'] = null;
        $validated['class_name'] = null;
        $validated['class_type'] = null;
        $validated['location'] = null;

        ClassSchedule::create($validated);

        return redirect()->route('admin.classes.calendar')
            ->with('success', 'Schedule created successfully!');
    }

    // Edit Schedule
    public function editSchedule($id)
    {
        $schedule = ClassSchedule::with(['dojo', 'instructor'])->findOrFail($id);
        $dojos = Dojo::all();
        $instructors = \App\Models\Instructor::with('user')->get();
        
        return view('admin.classes.edit-schedule', compact('schedule', 'dojos', 'instructors'));
    }

    // Update Schedule
    public function updateSchedule(Request $request, $id)
    {
        $schedule = ClassSchedule::findOrFail($id);

        $validated = $request->validate([
            'dojo_id' => 'required|exists:dojos,id',
            'instructor_id' => 'nullable|exists:instructors,id',
            'day_of_week' => 'required|integer|between:0,6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $schedule->update($validated);

        return redirect()->route('admin.classes.calendar')
            ->with('success', 'Schedule updated successfully!');
    }

    // Delete Schedule
    public function destroySchedule($id)
    {
        $schedule = ClassSchedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('admin.classes.calendar')
            ->with('success', 'Schedule deleted successfully!');
    }

    // Deteksi Bentrok Jadwal (Global)
    public function conflicts(Request $request)
    {
        // Find schedule conflicts (same instructor, overlapping time, same day_of_week)
        $schedules = ClassSchedule::with(['dojoclass.dojo', 'instructor'])
            ->where('is_active', true)
            ->get();

        $conflicts = [];
        
        foreach ($schedules as $schedule) {
            // Check if schedule has an instructor
            if ($schedule->instructor) {
                $instructor = $schedule->instructor;
                $conflicting = ClassSchedule::where('instructor_id', $instructor->id)
                    ->where('id', '!=', $schedule->id)
                    ->where('day_of_week', $schedule->day_of_week)
                    ->where(function($q) use ($schedule) {
                        $q->whereBetween('start_time', [$schedule->start_time, $schedule->end_time])
                          ->orWhereBetween('end_time', [$schedule->start_time, $schedule->end_time])
                          ->orWhere(function($q2) use ($schedule) {
                              $q2->where('start_time', '<=', $schedule->start_time)
                                 ->where('end_time', '>=', $schedule->end_time);
                          });
                    })
                    ->get();

                if ($conflicting->count() > 0) {
                    $conflicts[] = [
                        'schedule' => $schedule,
                        'instructor' => $instructor,
                        'conflicting' => $conflicting,
                    ];
                }
            }
        }

        $dojos = Dojo::all();

        return view('admin.classes.conflicts', compact('conflicts', 'dojos'));
    }

    // Weekly Calendar - Show all weekly schedules
    public function calendar(Request $request)
    {
        $dojos = Dojo::all();
        
        return view('admin.classes.calendar', compact('dojos'));
    }
}
