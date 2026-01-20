<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Member;
use App\Models\Invoice;

class CheckUnpaidRegistration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Only check for students
        if ($user && $user->hasRole('student')) {
            $dojoId = currentDojo();
            
            // Get member
            $member = Member::where('user_id', $user->id)
                ->where('dojo_id', $dojoId)
                ->first();

            if ($member) {
                // Check for unpaid registration invoice
                $unpaidRegistration = Invoice::where('member_id', $member->id)
                    ->where('type', 'registration')
                    ->whereIn('status', ['pending', 'overdue'])
                    ->exists();

                // If unpaid registration exists and not already on payment page, redirect
                if ($unpaidRegistration && !$request->is('student/payments*')) {
                    return redirect()->route('student.payments.index')
                        ->with('payment_required', true);
                }
            }
        }

        return $next($request);
    }
}

