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
    // Template Kelas
    public function templates(Request $request)
    {
        $query = DojoClass::with(['dojo']);

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

    // Monitoring Jadwal Cabang
    public function monitoring(Request $request)
    {
        $query = ClassSchedule::with(['dojoclass.dojo', 'instructor']);

        if ($request->has('dojo_id')) {
            $query->whereHas('dojoclass', function($q) use ($request) {
                $q->where('dojo_id', $request->dojo_id);
            });
        }

        if ($request->has('day_of_week')) {
            $query->where('day_of_week', $request->day_of_week);
        }

        $schedules = $query->orderBy('day_of_week')->orderBy('start_time')->paginate(20);
        $dojos = Dojo::all();

        return view('admin.classes.monitoring', compact('schedules', 'dojos'));
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

    // Kalender Global
    public function calendar(Request $request)
    {
        $date = $request->get('date', Carbon::now()->format('Y-m-d'));
        $dayOfWeek = Carbon::parse($date)->dayOfWeek; // 0 = Sunday, 6 = Saturday
        $dojoId = $request->get('dojo_id');

        $query = ClassSchedule::with(['dojoclass.dojo', 'instructor'])
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true);

        if ($dojoId) {
            $query->whereHas('dojoclass', function($q) use ($dojoId) {
                $q->where('dojo_id', $dojoId);
            });
        }

        $schedules = $query->orderBy('start_time')->get();
        $dojos = Dojo::all();

        return view('admin.classes.calendar', compact('schedules', 'dojos', 'date'));
    }
}
