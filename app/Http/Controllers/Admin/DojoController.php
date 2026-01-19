<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dojo;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class DojoController extends Controller
{
    public function index()
    {
        $dojos = Dojo::withCount(['members', 'users', 'classes'])
            ->with(['users' => function($query) {
                $query->whereHas('roles', function($q) {
                    $q->where('name', 'owner');
                });
            }])
            ->paginate(20);
            
        return view('admin.dojos.index', compact('dojos'));
    }

    public function create()
    {
        return view('admin.dojos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website_url' => 'nullable|url|max:255',
        ]);

        Dojo::create($validated);

        return redirect()->route('admin.dojos.index')
            ->with('success', 'Dojo created successfully.');
    }

    public function show(Dojo $dojo)
    {
        $dojo->loadCount(['members', 'users', 'classes', 'instructors']);
        return view('admin.dojos.show', compact('dojo'));
    }

    public function edit(Dojo $dojo)
    {
        return view('admin.dojos.edit', compact('dojo'));
    }

    public function update(Request $request, Dojo $dojo)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website_url' => 'nullable|url|max:255',
        ]);

        $dojo->update($validated);

        return redirect()->route('admin.dojos.show', $dojo)
            ->with('success', 'Dojo updated successfully.');
    }

    public function destroy(Dojo $dojo)
    {
        $dojo->delete();
        return redirect()->route('admin.dojos.index')
            ->with('success', 'Dojo deleted successfully.');
    }
    
    public function assignOwnerForm(Dojo $dojo)
    {
        // Get users without owner role at this dojo or users who are potential owners
        $availableUsers = User::whereDoesntHave('roles', function($query) use ($dojo) {
            $query->where('name', 'owner')
                  ->where('user_roles.dojo_id', $dojo->id);
        })->where('status', 'active')->get();
        
        return view('admin.dojos.assign-owner', compact('dojo', 'availableUsers'));
    }
    
    public function assignOwner(Request $request, Dojo $dojo)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);
        
        $user = User::findOrFail($validated['user_id']);
        $ownerRole = Role::where('name', 'owner')->first();
        
        if (!$ownerRole) {
            return redirect()->back()->with('error', 'Owner role not found in system.');
        }
        
        // Check if user already has owner role for this dojo
        $hasOwnerRole = $user->roles()
            ->where('name', 'owner')
            ->wherePivot('dojo_id', $dojo->id)
            ->exists();
        
        if ($hasOwnerRole) {
            return redirect()->back()->with('error', 'User is already an owner of this dojo.');
        }
        
        // Assign owner role
        $user->roles()->attach($ownerRole->id, [
            'dojo_id' => $dojo->id,
            'assigned_at' => now(),
            'assigned_by_user_id' => auth()->id(),
        ]);
        
        return redirect()->route('admin.dojos.index')
            ->with('success', "Owner assigned successfully to {$dojo->name}!");
    }
}

