<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ParentRegistrationMail;
use App\Models\User;
use App\Models\Member;
use App\Models\Dojo;
use App\Models\ParentRegistrationToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ParentRegisterController extends Controller
{
    public function showEmailForm()
    {
        // If already authenticated, redirect to appropriate dashboard
        if (auth()->check()) {
            $user = auth()->user();
            
            if ($user->hasRole('parent')) {
                return redirect()->route('parent.dashboard');
            }
            
            return redirect()->route('dashboard');
        }
        
        return view('auth.parent-register');
    }

    public function sendRegistrationLink(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
        ]);

        // Generate token
        $token = Str::random(60);
        
        // Delete old tokens for this email
        ParentRegistrationToken::where('email', $validated['email'])->delete();
        
        // Store token in DATABASE (not session)
        ParentRegistrationToken::create([
            'email' => $validated['email'],
            'token' => $token,
            'expires_at' => now()->addHours(24),
        ]);

        // Send email with registration link
        try {
            Mail::to($validated['email'])->send(new ParentRegistrationMail($token));
            
            return redirect()->route('parent.register.email')
                ->with('success', 'Registration link has been sent to your email. Please check your inbox.');
        } catch (\Exception $e) {
            return redirect()->route('parent.register.email')
                ->with('error', 'Failed to send email. Please try again.');
        }
    }

    public function showRegistrationForm($token)
    {
        // If already authenticated, redirect to appropriate dashboard
        if (auth()->check()) {
            return redirect()->route('parent.dashboard');
        }
        
        // Verify token from DATABASE
        $registrationToken = ParentRegistrationToken::where('token', $token)->first();
        
        if (!$registrationToken) {
            abort(403, 'Invalid registration link.');
        }

        if ($registrationToken->isExpired()) {
            $registrationToken->delete();
            abort(403, 'Registration link has expired. Please request a new one.');
        }

        $email = $registrationToken->email;

        return view('auth.parent-complete-registration', compact('token', 'email'));
    }

    public function completeRegistration(Request $request, $token)
    {
        try {
            // Verify token from DATABASE
            $registrationToken = ParentRegistrationToken::where('token', $token)->first();
            
            if (!$registrationToken) {
                return redirect()->route('login')
                    ->with('error', 'Invalid registration link. Please request a new one.');
            }

            if ($registrationToken->isExpired()) {
                $registrationToken->delete();
                return redirect()->route('login')
                    ->with('error', 'Registration link has expired. Please request a new one.');
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
                'phone' => 'nullable|string|max:20',
            ]);

            // Verify email matches
            if ($validated['email'] !== $registrationToken->email) {
                return back()
                    ->withInput()
                    ->with('error', 'Email does not match the registration request.');
            }

            // Auto-assign random dojo to parent
            $randomDojo = Dojo::inRandomOrder()->first();
            
            // Create user with random dojo
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'],
                'dojo_id' => $randomDojo ? $randomDojo->id : null,
                'is_active' => true,
            ]);

            // Assign parent role in the assigned dojo
            if ($randomDojo) {
                \DB::table('user_roles')->insert([
                    'user_id' => $user->id,
                    'role_id' => \DB::table('roles')->where('name', 'parent')->value('id'),
                    'dojo_id' => $randomDojo->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Delete used token
            $registrationToken->delete();

            // Redirect to login with success message
            return redirect()->route('login')
                ->with('success', 'Registration completed successfully! You can now login with your credentials.');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Registration failed. Please try again or contact support.');
        }
    }
}
