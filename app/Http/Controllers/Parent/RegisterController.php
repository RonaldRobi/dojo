<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\User;
use App\Models\ParentStudent;
use App\Services\MemberService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    protected $memberService;

    public function __construct(MemberService $memberService)
    {
        $this->memberService = $memberService;
    }

    public function create()
    {
        // Get all dojos for selection
        $dojos = \App\Models\Dojo::orderBy('name')
            ->get(['id', 'name', 'address']);
        
        // Get registration fee and uniform price from system settings
        $registrationFee = \App\Models\SystemSetting::where('key', 'registration_fee')->value('value') ?? 150;
        $uniformPrice = \App\Models\SystemSetting::where('key', 'uniform_price')->value('value') ?? 100;
        
        return view('parent.register.create', compact('dojos', 'registrationFee', 'uniformPrice'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'dojo_id' => 'required|exists:dojos,id',
            'name' => 'required|string|max:255',
            // Allow username (alphanumeric + underscore, 3-20 chars) OR email format
            'email' => [
                'required',
                'string',
                'max:255',
                'unique:users,email',
                function ($attribute, $value, $fail) {
                    // Must be either valid email OR valid username format
                    $isEmail = filter_var($value, FILTER_VALIDATE_EMAIL);
                    $isUsername = preg_match('/^[a-zA-Z0-9_]{3,20}$/', $value);
                    
                    if (!$isEmail && !$isUsername) {
                        $fail('The username/email must be a valid email address or a username (3-20 characters, letters, numbers, and underscores only).');
                    }
                },
            ],
            'password' => 'required|string|min:8|confirmed',
            'date_of_birth' => 'required|date',
            'medical_notes' => 'nullable|string',
            'package_type' => 'required|in:registration_only,registration_with_uniform',
        ]);
        
        // Use selected dojo instead of currentDojo()
        $dojoId = $validated['dojo_id'];

        DB::beginTransaction();
        try {
            // Get parent's member data for phone and address
            $parentMember = Member::where('user_id', $user->id)->first();
            
            // Create user account for the child
            $childUser = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'dojo_id' => $dojoId,
                'status' => 'active',
            ]);

            // Assign student role
            $studentRole = \App\Models\Role::where('name', 'student')->first();
            if ($studentRole) {
                $childUser->roles()->attach($studentRole->id, [
                    'dojo_id' => $dojoId,
                    'assigned_at' => now(),
                    'assigned_by_user_id' => $user->id,
                ]);
            }

            // Create member record with parent's phone and address
            $memberData = [
                'user_id' => $childUser->id,
                'name' => $validated['name'],
                'birth_date' => $validated['date_of_birth'],
                'gender' => null, // To be filled by student later
                'phone' => $parentMember ? $parentMember->phone : null,
                'address' => $parentMember ? $parentMember->address : null,
                'status' => 'active',
                'join_date' => now(),
                'medical_notes' => $validated['medical_notes'] ?? null,
            ];

            $member = $this->memberService->create($memberData, \App\Models\Dojo::findOrFail($dojoId));

            // Link parent to child
            ParentStudent::create([
                'parent_user_id' => $user->id,
                'member_id' => $member->id,
                'dojo_id' => $dojoId,
                'linked_by_user_id' => $user->id,
                'linked_at' => now(),
            ]);

            // Ensure parent has role in this dojo
            $parentRole = \App\Models\Role::where('name', 'parent')->first();
            if ($parentRole && !$user->roles()->wherePivot('dojo_id', $dojoId)->exists()) {
                $user->roles()->attach($parentRole->id, [
                    'dojo_id' => $dojoId,
                    'assigned_at' => now(),
                    'assigned_by_user_id' => $user->id,
                ]);
            }

            DB::commit();

            // Store package type in session for payment page
            session(['child_package_type' => $validated['package_type']]);

            // Redirect to payment page for registration fee
            return redirect()->route('parent.payment.registration', $member->id)
                ->with('success', 'Child registered successfully. Please complete the payment to activate the membership.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'An error occurred while registering your child: ' . $e->getMessage()]);
        }
    }

    /**
     * Check if username/email is available (API endpoint for realtime validation)
     */
    public function checkUsernameAvailability(Request $request)
    {
        $username = $request->query('username');
        
        if (!$username) {
            return response()->json([
                'available' => false,
                'message' => 'Username is required'
            ], 400);
        }

        // Check if username/email already exists
        $exists = User::where('email', $username)->exists();

        if ($exists) {
            return response()->json([
                'available' => false,
                'message' => 'This username/email is already taken'
            ]);
        }

        // Validate format
        $isEmail = filter_var($username, FILTER_VALIDATE_EMAIL);
        $isUsername = preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username);

        if (!$isEmail && !$isUsername) {
            return response()->json([
                'available' => false,
                'message' => 'Invalid format. Use 3-20 characters (letters, numbers, underscores) or a valid email'
            ]);
        }

        return response()->json([
            'available' => true,
            'message' => 'Username/email is available',
            'type' => $isEmail ? 'email' : 'username'
        ]);
    }
}

