<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function index(Request $request)
    {
        $dojoId = currentDojo();
        
        $query = Membership::where('dojo_id', $dojoId)
            ->with(['member']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('member', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $memberships = $query->latest()->paginate(20);

        return view('finance.memberships.index', compact('memberships'));
    }

    public function create()
    {
        return view('finance.memberships.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'price' => 'required|numeric|min:0',
        ]);

        $validated['dojo_id'] = currentDojo();
        $membership = Membership::create($validated);

        return redirect()->route('finance.memberships.show', $membership)
            ->with('success', 'Membership created successfully.');
    }

    public function show(Membership $membership)
    {
        $membership->load(['member']);
        return view('finance.memberships.show', compact('membership'));
    }

    public function edit(Membership $membership)
    {
        return view('finance.memberships.edit', compact('membership'));
    }

    public function update(Request $request, Membership $membership)
    {
        $validated = $request->validate([
            'type' => 'sometimes|required|string',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after:start_date',
            'price' => 'sometimes|required|numeric|min:0',
            'status' => 'sometimes|required|string',
        ]);

        $membership->update($validated);

        return redirect()->route('finance.memberships.show', $membership)
            ->with('success', 'Membership updated successfully.');
    }

    public function destroy(Membership $membership)
    {
        $membership->delete();
        return redirect()->route('finance.memberships.index')
            ->with('success', 'Membership deleted successfully.');
    }
}
