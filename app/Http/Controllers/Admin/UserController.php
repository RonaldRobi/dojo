<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Dojo;
use App\Services\UserService;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    protected $userService;
    protected $roleService;

    public function __construct(UserService $userService, RoleService $roleService)
    {
        $this->userService = $userService;
        $this->roleService = $roleService;
    }

    public function index(Request $request)
    {
        $query = User::with(['dojo', 'roles']);

        if ($request->has('dojo_id')) {
            $query->where('dojo_id', $request->dojo_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(20);
        $dojos = Dojo::all();
        $roles = \App\Models\Role::all();

        return view('admin.users.index', compact('users', 'dojos', 'roles'));
    }

    public function create()
    {
        $dojos = Dojo::all();
        $roles = \App\Models\Role::all();
        return view('admin.users.create', compact('dojos', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'dojo_id' => 'nullable|exists:dojos,id',
            'status' => 'nullable|in:active,suspended',
            'roles' => 'nullable|array',
            'roles.*.role' => 'required|string',
            'roles.*.dojo_id' => 'required|exists:dojos,id',
        ]);

        $user = $this->userService->create($validated, auth()->user());

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user->load(['dojo', 'roles', 'userRoles']);
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $dojos = Dojo::all();
        $roles = \App\Models\Role::all();
        $user->load(['dojo', 'roles']);
        return view('admin.users.edit', compact('user', 'dojos', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|nullable|string|min:8',
            'dojo_id' => 'nullable|exists:dojos,id',
            'status' => 'sometimes|in:active,suspended',
        ]);

        $user = $this->userService->update($user, $validated, auth()->user());

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
