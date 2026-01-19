<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\Instructor;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $dojoId = currentDojo();

        // Get instructor record
        $instructor = Instructor::where('user_id', $user->id)
            ->where('dojo_id', $dojoId)
            ->first();

        // If instructor record doesn't exist, create one automatically
        if (!$instructor) {
            $instructor = Instructor::create([
                'user_id' => $user->id,
                'dojo_id' => $dojoId,
                'name' => $user->name,
                'email' => $user->email,
                'status' => 'active',
            ]);
        }

        $today = now();
        $dayOfWeek = $today->dayOfWeek;

        $stats = [
            'instructor' => $instructor,
            'today_classes' => ClassSchedule::where('instructor_id', $instructor->id)
                ->where('day_of_week', $dayOfWeek)
                ->where('is_active', true)
                ->with(['enrollments.member'])
                ->get(),
            'total_students' => \App\Models\ClassEnrollment::whereHas('classSchedule', function($q) use ($instructor) {
                $q->where('instructor_id', $instructor->id);
            })->where('status', 'active')->distinct('member_id')->count(),
            'upcoming_classes' => ClassSchedule::where('instructor_id', $instructor->id)
                ->where('is_active', true)
                ->orderBy('day_of_week')
                ->orderBy('start_time')
                ->limit(5)
                ->get(),
            'this_week_attendance' => \App\Models\Attendance::whereHas('classSchedule', function($q) use ($instructor) {
                $q->where('instructor_id', $instructor->id);
            })
            ->whereBetween('attendance_date', [now()->startOfWeek(), now()->endOfWeek()])
            ->where('status', 'present')
            ->count(),
        ];

        return view('dashboard.coach', compact('stats', 'instructor'));
    }
}
