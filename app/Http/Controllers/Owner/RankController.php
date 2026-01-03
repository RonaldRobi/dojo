<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Rank;
use Illuminate\Http\Request;

class RankController extends Controller
{
    public function index(Request $request)
    {
        $query = Rank::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('belt_color', 'like', "%{$search}%");
            });
        }

        $ranks = $query->orderBy('level')->paginate(20);

        return view('owner.ranks.index', compact('ranks'));
    }

    public function create()
    {
        return view('owner.ranks.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'belt_color' => 'required|string|max:255',
            'level' => 'required|integer',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
        ]);

        $rank = Rank::create($validated);

        return redirect()->route('owner.ranks.show', $rank)
            ->with('success', 'Rank created successfully.');
    }

    public function show(Rank $rank)
    {
        return view('owner.ranks.show', compact('rank'));
    }

    public function edit(Rank $rank)
    {
        return view('owner.ranks.edit', compact('rank'));
    }

    public function update(Request $request, Rank $rank)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'belt_color' => 'sometimes|required|string|max:255',
            'level' => 'sometimes|required|integer',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
        ]);

        $rank->update($validated);

        return redirect()->route('owner.ranks.show', $rank)
            ->with('success', 'Rank updated successfully.');
    }

    public function destroy(Rank $rank)
    {
        $rank->delete();
        return redirect()->route('owner.ranks.index')
            ->with('success', 'Rank deleted successfully.');
    }
}
