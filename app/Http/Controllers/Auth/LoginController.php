<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\UserService;

class LoginController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function showLoginForm()
    {
        // If already authenticated, redirect to appropriate dashboard
        if (auth()->check()) {
            $user = auth()->user();
            
            if ($user->hasRole('super_admin')) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->hasRole('owner')) {
                return redirect()->route('owner.dashboard');
            } elseif ($user->hasRole('finance')) {
                return redirect()->route('finance.dashboard');
            } elseif ($user->hasRole('coach')) {
                return redirect()->route('coach.dashboard');
            } elseif ($user->hasRole('student')) {
                return redirect()->route('student.dashboard');
            } elseif ($user->hasRole('parent')) {
                return redirect()->route('parent.dashboard');
            }
            
            return redirect()->route('dashboard');
        }
        
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Update last login
            $this->userService->updateLastLogin($user);

            // Redirect based on role
            if ($user->hasRole('super_admin')) {
                return redirect()->intended(route('admin.dashboard'));
            } elseif ($user->hasRole('owner')) {
                return redirect()->intended(route('owner.dashboard'));
            } elseif ($user->hasRole('finance')) {
                return redirect()->intended(route('finance.dashboard'));
            } elseif ($user->hasRole('coach')) {
                return redirect()->intended(route('coach.dashboard'));
            } elseif ($user->hasRole('student')) {
                return redirect()->intended(route('student.dashboard'));
            } elseif ($user->hasRole('parent')) {
                return redirect()->intended(route('parent.dashboard'));
            }

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

