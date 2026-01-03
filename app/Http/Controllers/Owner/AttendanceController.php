<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Member;
use App\Models\ClassSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $dojoId = currentDojo();
        
        $query = Attendance::whereHas('member', function($q) use ($dojoId) {
            $q->where('dojo_id', $dojoId);
        })->with(['member', 'classSchedule.dojoClass']);

        if ($request->has('member_id')) {
            $query->where('member_id', $request->member_id);
        }

        if ($request->has('date_from')) {
            $query->where('attendance_date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->where('attendance_date', '<=', $request->date_to);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('member', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $attendances = $query->latest('attendance_date')->paginate(50);
        $members = Member::where('dojo_id', $dojoId)->get();

        return view('owner.attendances.index', compact('attendances', 'members'));
    }

    public function create()
    {
        $dojoId = currentDojo();
        $members = Member::where('dojo_id', $dojoId)->get();
        $schedules = ClassSchedule::whereHas('dojoClass', function($q) use ($dojoId) {
            $q->where('dojo_id', $dojoId);
        })->with('dojoClass')->get();
        
        return view('owner.attendances.create', compact('members', 'schedules'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'class_schedule_id' => 'required|exists:class_schedules,id',
            'attendance_date' => 'required|date',
            'status' => 'required|in:present,absent,excused',
            'checked_in_method' => 'nullable|in:qr,manual',
            'notes' => 'nullable|string',
        ]);

        $attendance = Attendance::create($validated);

        return redirect()->route('owner.attendances.show', $attendance)
            ->with('success', 'Attendance recorded successfully.');
    }

    public function show(Attendance $attendance)
    {
        $attendance->load(['member', 'classSchedule.dojoClass']);
        return view('owner.attendances.show', compact('attendance'));
    }

    public function edit(Attendance $attendance)
    {
        $dojoId = currentDojo();
        $members = Member::where('dojo_id', $dojoId)->get();
        $schedules = ClassSchedule::whereHas('dojoClass', function($q) use ($dojoId) {
            $q->where('dojo_id', $dojoId);
        })->with('dojoClass')->get();
        
        return view('owner.attendances.edit', compact('attendance', 'members', 'schedules'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'member_id' => 'sometimes|required|exists:members,id',
            'class_schedule_id' => 'sometimes|required|exists:class_schedules,id',
            'attendance_date' => 'sometimes|required|date',
            'status' => 'sometimes|required|in:present,absent,excused',
            'checked_in_method' => 'nullable|in:qr,manual',
            'notes' => 'nullable|string',
        ]);

        $attendance->update($validated);

        return redirect()->route('owner.attendances.show', $attendance)
            ->with('success', 'Attendance updated successfully.');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return redirect()->route('owner.attendances.index')
            ->with('success', 'Attendance deleted successfully.');
    }
}
