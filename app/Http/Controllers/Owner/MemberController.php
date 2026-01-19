<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Dojo;
use App\Services\MemberService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MemberController extends Controller
{
    protected $memberService;

    public function __construct(MemberService $memberService)
    {
        $this->memberService = $memberService;
    }

    public function index(Request $request)
    {
        $dojoId = currentDojo();
        
        $query = Member::where('dojo_id', $dojoId)
            ->with(['user', 'currentBelt', 'parents']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('email', 'like', "%{$search}%");
                  });
            });
        }

        $members = $query->paginate(20);

        return view('owner.members.index', compact('members'));
    }

    public function create()
    {
        return view('owner.members.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'status' => 'nullable|in:active,leave,inactive',
            'join_date' => 'nullable|date',
            'style' => 'nullable|string',
            'medical_notes' => 'nullable|string',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'nullable|string|min:8',
        ]);

        $dojo = Dojo::findOrFail(currentDojo());
        
        // Create user if email provided
        if (isset($validated['email'])) {
            $user = \App\Models\User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password'] ?? 'password'),
                'dojo_id' => $dojo->id,
                'status' => 'active',
            ]);
            $validated['user_id'] = $user->id;
        }

        unset($validated['email'], $validated['password']);
        
        $member = $this->memberService->create($validated, $dojo);

        return redirect()->route('owner.members.show', $member)
            ->with('success', 'Member created successfully.');
    }

    public function show(Member $member)
    {
        Gate::authorize('view', $member);
        
        $member->load(['dojo', 'user', 'currentBelt', 'parents', 'attendances', 'enrollments']);
        return view('owner.members.show', compact('member'));
    }

    public function edit(Member $member)
    {
        Gate::authorize('update', $member);
        
        $member->load(['user']);
        return view('owner.members.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        Gate::authorize('update', $member);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'status' => 'sometimes|in:active,leave,inactive',
            'style' => 'nullable|string',
            'medical_notes' => 'nullable|string',
        ]);

        $member = $this->memberService->update($member, $validated);

        return redirect()->route('owner.members.show', $member)
            ->with('success', 'Member updated successfully.');
    }

    public function destroy(Member $member)
    {
        Gate::authorize('delete', $member);
        
        $member->delete();
        
        return redirect()->route('owner.members.index')
            ->with('success', 'Member deleted successfully.');
    }

    public function regenerateQR(Member $member)
    {
        $this->memberService->generateQRCode($member);
        return redirect()->back()->with('success', 'QR Code regenerated successfully.');
    }

    public function attendance()
    {
        return view('owner.members.attendance');
    }

    public function bulkStoreAttendance(Request $request)
    {
        $dojoId = currentDojo();
        
        $validated = $request->validate([
            'schedule_id' => 'required|exists:class_schedules,id',
            'attendance_date' => 'required|date',
            'students' => 'required|array',
            'students.*.member_id' => 'required|exists:members,id',
            'students.*.status' => 'required|in:present,late,absent,excused',
            'students.*.notes' => 'nullable|string',
        ]);

        $scheduleId = $validated['schedule_id'];
        $attendanceDate = $validated['attendance_date'];

        foreach ($validated['students'] as $studentData) {
            // Verify member belongs to owner's dojo
            $member = Member::findOrFail($studentData['member_id']);
            if ($member->dojo_id !== $dojoId) {
                continue; // Skip if not owner's member
            }

            \App\Models\Attendance::updateOrCreate(
                [
                    'member_id' => $studentData['member_id'],
                    'class_schedule_id' => $scheduleId,
                    'attendance_date' => $attendanceDate,
                ],
                [
                    'status' => $studentData['status'],
                    'notes' => $studentData['notes'] ?? null,
                    'checked_in_at' => now(),
                    'checked_in_method' => 'owner_bulk',
                ]
            );
        }

        return back()->with('success', 'Attendance recorded successfully.');
    }
}
