<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dojo;
use Illuminate\Http\Request;

class DojoController extends Controller
{
    public function index()
    {
        $dojos = Dojo::withCount(['members', 'users', 'classes'])->paginate(20);
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
}

