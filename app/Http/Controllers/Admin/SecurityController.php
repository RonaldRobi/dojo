<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SecurityService;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    protected $securityService;

    public function __construct(SecurityService $securityService)
    {
        $this->securityService = $securityService;
    }

    public function checkPasswordStrength(Request $request)
    {
        $validated = $request->validate([
            'password' => 'required|string',
        ]);

        $result = $this->securityService->checkPasswordStrength($validated['password']);
        return response()->json($result);
    }

    public function getSecurityStats()
    {
        $stats = [
            'suspicious_activities' => \App\Models\AuditLog::where('action', 'suspicious_activity')
                ->whereDate('created_at', today())
                ->count(),
            'failed_logins_today' => \App\Models\AuditLog::where('action', 'failed_login')
                ->whereDate('created_at', today())
                ->count(),
        ];

        return response()->json($stats);
    }
}
