<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\DojoClass;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index(Request $request)
    {
        $dojoId = currentDojo();
        
        $query = DojoClass::where('dojo_id', $dojoId)
            ->with(['schedules.instructor']);

        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $classes = $query->paginate(20);

        return view('owner.classes.index', compact('classes'));
    }

    public function create()
    {
        return view('owner.classes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'level_min' => 'nullable|integer',
            'level_max' => 'nullable|integer',
            'age_min' => 'nullable|integer',
            'age_max' => 'nullable|integer',
            'style' => 'nullable|string',
            'capacity' => 'required|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['dojo_id'] = currentDojo();
        $class = DojoClass::create($validated);

        return redirect()->route('owner.classes.show', $class)
            ->with('success', 'Class created successfully.');
    }

    public function show(DojoClass $dojoClass)
    {
        $dojoClass->load(['schedules.instructor', 'enrollments.member']);
        return view('owner.classes.show', compact('dojoClass'));
    }

    public function edit(DojoClass $dojoClass)
    {
        return view('owner.classes.edit', compact('dojoClass'));
    }

    public function update(Request $request, DojoClass $dojoClass)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'level_min' => 'nullable|integer',
            'level_max' => 'nullable|integer',
            'age_min' => 'nullable|integer',
            'age_max' => 'nullable|integer',
            'style' => 'nullable|string',
            'capacity' => 'sometimes|required|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);

        $dojoClass->update($validated);

        return redirect()->route('owner.classes.show', $dojoClass)
            ->with('success', 'Class updated successfully.');
    }

    public function destroy(DojoClass $dojoClass)
    {
        $dojoClass->delete();
        return redirect()->route('owner.classes.index')
            ->with('success', 'Class deleted successfully.');
    }
}
