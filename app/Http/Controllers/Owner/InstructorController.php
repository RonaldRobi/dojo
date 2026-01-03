<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use Illuminate\Http\Request;

class InstructorController extends Controller
{
    public function index(Request $request)
    {
        $dojoId = currentDojo();
        
        $query = Instructor::where('dojo_id', $dojoId)
            ->with(['user', 'schedules']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $instructors = $query->paginate(20);

        return view('owner.instructors.index', compact('instructors'));
    }

    public function create()
    {
        return view('owner.instructors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'specialization' => 'nullable|string',
            'bio' => 'nullable|string',
            'hire_date' => 'nullable|date',
            'status' => 'nullable|in:active,inactive',
            'certification_level' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $validated['dojo_id'] = currentDojo();
        $instructor = Instructor::create($validated);

        return redirect()->route('owner.instructors.show', $instructor)
            ->with('success', 'Instructor created successfully.');
    }

    public function show(Instructor $instructor)
    {
        $instructor->load(['user', 'schedules.dojoClass']);
        return view('owner.instructors.show', compact('instructor'));
    }

    public function edit(Instructor $instructor)
    {
        return view('owner.instructors.edit', compact('instructor'));
    }

    public function update(Request $request, Instructor $instructor)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'specialization' => 'nullable|string',
            'bio' => 'nullable|string',
            'hire_date' => 'nullable|date',
            'status' => 'sometimes|in:active,inactive',
            'certification_level' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $instructor->update($validated);

        return redirect()->route('owner.instructors.show', $instructor)
            ->with('success', 'Instructor updated successfully.');
    }

    public function destroy(Instructor $instructor)
    {
        $instructor->delete();
        return redirect()->route('owner.instructors.index')
            ->with('success', 'Instructor deleted successfully.');
    }
}
