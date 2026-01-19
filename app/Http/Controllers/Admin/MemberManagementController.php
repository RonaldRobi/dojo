<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Attendance;
use App\Models\Dojo;
use App\Models\ClassSchedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MemberManagementController extends Controller
{
    // Data Seluruh Siswa
    public function index(Request $request)
    {
        $query = Member::with(['dojo', 'user']);

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('dojo_id')) {
            $query->where('dojo_id', $request->dojo_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $members = $query->orderBy('name')->paginate(20);
        $dojos = Dojo::all();

        return view('admin.members.index', compact('members', 'dojos'));
    }

    // Riwayat Kehadiran Global
    public function attendanceGlobal(Request $request)
    {
        // Get active schedules for today
        $today = Carbon::now();
        $dayOfWeek = $today->dayOfWeek; // 0 = Sunday, 6 = Saturday
        
        $activeSchedulesQuery = ClassSchedule::with(['dojo', 'instructor.user'])
            ->where('is_active', true)
            ->where('day_of_week', $dayOfWeek);
        
        // Filter by dojo if specified
        $selectedDojoId = $request->get('dojo_id');
        if ($selectedDojoId) {
            $activeSchedulesQuery->where('dojo_id', $selectedDojoId);
        }
        
        $activeSchedules = $activeSchedulesQuery->orderBy('start_time')->get();
        
        // Get members for attendance
        $membersQuery = Member::with(['dojo', 'currentBelt'])
            ->where('status', 'active');
            
        if ($selectedDojoId) {
            $membersQuery->where('dojo_id', $selectedDojoId);
        }
        
        $members = $membersQuery->orderBy('name')->get();
        
        // Get attendance history
        $attendanceQuery = Attendance::with(['member.dojo', 'classSchedule.dojo']);

        if ($request->has('member_id')) {
            $attendanceQuery->where('member_id', $request->member_id);
        }

        if ($selectedDojoId) {
            $attendanceQuery->whereHas('member', function($q) use ($selectedDojoId) {
                $q->where('dojo_id', $selectedDojoId);
            });
        }

        if ($request->has('date_from')) {
            $attendanceQuery->whereDate('attendance_date', '>=', $request->date_from);
        } else {
            // Default to last 30 days
            $attendanceQuery->whereDate('attendance_date', '>=', Carbon::now()->subDays(30));
        }

        if ($request->has('date_to')) {
            $attendanceQuery->whereDate('attendance_date', '<=', $request->date_to);
        }

        $attendances = $attendanceQuery->orderBy('attendance_date', 'desc')->paginate(20);
        $dojos = Dojo::all();

        return view('admin.members.attendance-global', compact(
            'attendances', 
            'members', 
            'dojos', 
            'activeSchedules',
            'selectedDojoId',
            'today'
        ));
    }
    
    // Store Attendance
    public function storeAttendance(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'class_schedule_id' => 'required|exists:class_schedules,id',
            'attendance_date' => 'required|date',
            'status' => 'required|in:present,late,absent,excused',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check if attendance already exists for this member, schedule, and date
        $existingAttendance = Attendance::where('member_id', $validated['member_id'])
            ->where('class_schedule_id', $validated['class_schedule_id'])
            ->whereDate('attendance_date', $validated['attendance_date'])
            ->first();

        if ($existingAttendance) {
            // Update existing attendance
            $existingAttendance->update([
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? null,
                'checked_in_at' => now(),
                'checked_in_method' => 'admin_manual',
            ]);
            
            return redirect()->back()->with('success', 'Attendance updated successfully!');
        }

        // Create new attendance
        Attendance::create([
            'member_id' => $validated['member_id'],
            'class_schedule_id' => $validated['class_schedule_id'],
            'attendance_date' => $validated['attendance_date'],
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
            'checked_in_at' => now(),
            'checked_in_method' => 'admin_manual',
        ]);

        return redirect()->back()->with('success', 'Attendance recorded successfully!');
    }
    
    // Bulk Store Attendance
    public function bulkStoreAttendance(Request $request)
    {
        $validated = $request->validate([
            'class_schedule_id' => 'required|exists:class_schedules,id',
            'attendance_date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.member_id' => 'required|exists:members,id',
            'attendances.*.status' => 'required|in:present,late,absent,excused',
            'attendances.*.notes' => 'nullable|string|max:500',
        ]);

        $count = 0;
        foreach ($validated['attendances'] as $attendanceData) {
            // Check if attendance already exists
            $existingAttendance = Attendance::where('member_id', $attendanceData['member_id'])
                ->where('class_schedule_id', $validated['class_schedule_id'])
                ->whereDate('attendance_date', $validated['attendance_date'])
                ->first();

            if ($existingAttendance) {
                // Update existing
                $existingAttendance->update([
                    'status' => $attendanceData['status'],
                    'notes' => $attendanceData['notes'] ?? null,
                    'checked_in_at' => now(),
                    'checked_in_method' => 'admin_bulk',
                ]);
            } else {
                // Create new
                Attendance::create([
                    'member_id' => $attendanceData['member_id'],
                    'class_schedule_id' => $validated['class_schedule_id'],
                    'attendance_date' => $validated['attendance_date'],
                    'status' => $attendanceData['status'],
                    'notes' => $attendanceData['notes'] ?? null,
                    'checked_in_at' => now(),
                    'checked_in_method' => 'admin_bulk',
                ]);
            }
            $count++;
        }

        return redirect()->back()->with('success', "Attendance recorded for {$count} students!");
    }

    // Status Keaktifan Siswa
    public function status(Request $request)
    {
        $query = Member::with(['dojo']);

        if ($request->has('dojo_id')) {
            $query->where('dojo_id', $request->dojo_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
    }

        // Group by status
        $stats = [
            'active' => Member::where('status', 'active')->count(),
            'inactive' => Member::where('status', 'inactive')->count(),
            'leave' => Member::where('status', 'leave')->count(),
            'total' => Member::count(),
        ];

        $members = $query->orderBy('name')->paginate(20);
        $dojos = Dojo::all();

        return view('admin.members.status', compact('members', 'dojos', 'stats'));
    }

    // Medical Notes & Waiver
    public function medicalNotes(Request $request)
    {
        $query = Member::with(['dojo'])
            ->where(function($q) {
                $q->whereNotNull('medical_notes')
                  ->orWhereNotNull('waiver_signed_at');
            });

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('dojo_id')) {
            $query->where('dojo_id', $request->dojo_id);
        }

        if ($request->has('has_waiver')) {
            if ($request->has_waiver == 'yes') {
                $query->whereNotNull('waiver_signed_at');
            } else {
                $query->whereNull('waiver_signed_at');
            }
        }

        $members = $query->orderBy('name')->paginate(20);
        $dojos = Dojo::all();

        return view('admin.members.medical-notes', compact('members', 'dojos'));
    }
}
