<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    public function index(Request $request)
    {
        $dojoId = currentDojo();
        
        $achievements = Achievement::where('dojo_id', $dojoId)
            ->orderBy('achieved_date', 'desc')
            ->orderBy('display_order')
            ->paginate(20);

        return response()->json($achievements);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_path' => 'nullable|string',
            'achieved_date' => 'required|date',
            'display_order' => 'nullable|integer',
        ]);

        $validated['dojo_id'] = currentDojo();
        $achievement = Achievement::create($validated);

        return response()->json($achievement, 201);
    }

    public function show(Achievement $achievement)
    {
        return response()->json($achievement);
    }

    public function update(Request $request, Achievement $achievement)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'image_path' => 'nullable|string',
            'achieved_date' => 'sometimes|required|date',
            'display_order' => 'nullable|integer',
        ]);

        $achievement->update($validated);

        return response()->json($achievement);
    }

    public function destroy(Achievement $achievement)
    {
        $achievement->delete();
        return response()->json(['message' => 'Achievement deleted successfully']);
    }
}
