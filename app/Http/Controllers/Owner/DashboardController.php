<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\DojoClass;
use App\Models\Invoice;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function index()
    {
        $dojoId = currentDojo();
        $dojo = \App\Models\Dojo::find($dojoId);

        $stats = [
            'active_members' => Member::where('dojo_id', $dojoId)->where('status', 'active')->count(),
            'total_classes' => DojoClass::where('dojo_id', $dojoId)->count(),
            'active_classes' => DojoClass::where('dojo_id', $dojoId)->where('is_active', true)->count(),
            'pending_invoices' => Invoice::where('dojo_id', $dojoId)->where('status', 'pending')->count(),
            'overdue_invoices' => Invoice::where('dojo_id', $dojoId)->where('status', 'overdue')->count(),
            'retention_rate' => $dojo ? $this->analyticsService->getRetentionRate($dojo) : ['retention_rate' => 0],
        ];

        return view('dashboard.owner', compact('stats'));
    }
}
