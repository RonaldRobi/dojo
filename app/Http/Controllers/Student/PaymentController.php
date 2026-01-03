<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $dojoId = currentDojo();

        $member = Member::where('user_id', $user->id)
            ->where('dojo_id', $dojoId)
            ->first();

        if (!$member) {
            abort(404, 'Member profile not found');
        }

        $invoices = \App\Models\Invoice::where('member_id', $member->id)
            ->with(['items', 'payments'])
            ->latest()
            ->paginate(20);

        $totalDue = \App\Models\Invoice::where('member_id', $member->id)
            ->where('status', '!=', 'paid')
            ->sum('total_amount');

        return view('student.payments.index', compact('invoices', 'member', 'totalDue'));
    }

    public function show($invoiceId)
    {
        $user = auth()->user();
        $member = Member::where('user_id', $user->id)->first();

        $invoice = \App\Models\Invoice::where('id', $invoiceId)
            ->where('member_id', $member->id)
            ->with(['items', 'payments', 'member'])
            ->firstOrFail();

        return view('student.payments.show', compact('invoice', 'member'));
    }
}

