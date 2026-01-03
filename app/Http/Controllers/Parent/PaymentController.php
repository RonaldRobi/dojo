<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $dojoId = currentDojo();

        $childId = $request->input('child_id');
        
        $children = Member::whereHas('parents', function($q) use ($user, $dojoId) {
            $q->where('parent_user_id', $user->id)
              ->where('dojo_id', $dojoId);
        })->get();

        $selectedChild = $childId 
            ? $children->firstWhere('id', $childId)
            : $children->first();

        if (!$selectedChild) {
            return view('parent.payments.index', ['children' => $children]);
        }

        $invoices = \App\Models\Invoice::where('member_id', $selectedChild->id)
            ->with(['items', 'payments'])
            ->latest()
            ->paginate(20);

        $totalDue = \App\Models\Invoice::where('member_id', $selectedChild->id)
            ->where('status', '!=', 'paid')
            ->sum('total_amount');

        return view('parent.payments.index', compact('children', 'selectedChild', 'invoices', 'totalDue'));
    }

    public function show($invoiceId, Request $request)
    {
        $user = auth()->user();
        $dojoId = currentDojo();

        $invoice = \App\Models\Invoice::findOrFail($invoiceId);

        // Verify this invoice belongs to one of the user's children
        $isChild = Member::where('id', $invoice->member_id)
            ->whereHas('parents', function($q) use ($user, $dojoId) {
                $q->where('parent_user_id', $user->id)
                  ->where('dojo_id', $dojoId);
            })
            ->exists();

        if (!$isChild) {
            abort(403, 'You do not have access to this invoice.');
        }

        $invoice->load(['items', 'payments', 'member']);

        return view('parent.payments.show', compact('invoice'));
    }
}

