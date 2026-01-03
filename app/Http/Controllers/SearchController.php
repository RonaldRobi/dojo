<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use App\Models\DojoClass;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Event;
use App\Models\Instructor;
use App\Models\Dojo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([
                'results' => [],
                'query' => $query
            ]);
        }

        $results = [
            'members' => [],
            'users' => [],
            'invoices' => [],
            'payments' => [],
            'events' => [],
            'instructors' => [],
            'classes' => [],
            'dojos' => [],
        ];

        $user = auth()->user();

        // Search Members
        if ($user->hasRole('super_admin')) {
            $results['members'] = Member::where('name', 'like', "%{$query}%")
                ->orWhere('phone', 'like', "%{$query}%")
                ->with('dojo')
                ->limit(5)
                ->get()
                ->map(function($member) {
                    return [
                        'id' => $member->id,
                        'name' => $member->name,
                        'type' => 'Member',
                        'url' => route('admin.members.index') . '?search=' . urlencode($member->name),
                        'description' => $member->dojo->name ?? 'N/A',
                    ];
                });
        } elseif ($user->hasRole(['owner', 'finance'])) {
            $dojoId = currentDojo();
            $results['members'] = Member::where('dojo_id', $dojoId)
                ->where(function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('phone', 'like', "%{$query}%");
                })
                ->limit(5)
                ->get()
                ->map(function($member) {
                    return [
                        'id' => $member->id,
                        'name' => $member->name,
                        'type' => 'Member',
                        'url' => route('owner.members.index') . '?search=' . urlencode($member->name),
                        'description' => $member->phone ?? 'N/A',
                    ];
                });
        }

        // Search Users (Admin only)
        if ($user->hasRole('super_admin')) {
            $results['users'] = User::where('name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->limit(5)
                ->get()
                ->map(function($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'type' => 'User',
                        'url' => route('admin.users.show', $user->id),
                        'description' => $user->email,
                    ];
                });
        }

        // Search Invoices
        if ($user->hasRole(['super_admin', 'finance', 'owner'])) {
            $queryBuilder = Invoice::where('invoice_number', 'like', "%{$query}%");
            
            if ($user->hasRole(['finance', 'owner'])) {
                $dojoId = currentDojo();
                $queryBuilder->where('dojo_id', $dojoId);
            }

            $results['invoices'] = $queryBuilder
                ->with('member')
                ->limit(5)
                ->get()
                ->map(function($invoice) use ($user) {
                    if ($user->hasRole('super_admin')) {
                        $route = route('admin.finance.invoices.index') . '?search=' . urlencode($invoice->invoice_number);
                    } else {
                        $route = route('finance.invoices.show', $invoice->id);
                    }
                    
                    return [
                        'id' => $invoice->id,
                        'name' => $invoice->invoice_number,
                        'type' => 'Invoice',
                        'url' => $route,
                        'description' => $invoice->member->name ?? 'N/A',
                    ];
                });
        }

        // Search Payments
        if ($user->hasRole(['super_admin', 'finance', 'owner'])) {
            $queryBuilder = Payment::where('reference_number', 'like', "%{$query}%");
            
            if ($user->hasRole(['finance', 'owner'])) {
                $dojoId = currentDojo();
                $queryBuilder->whereHas('invoice', function($q) use ($dojoId) {
                    $q->where('dojo_id', $dojoId);
                });
            }

            $results['payments'] = $queryBuilder
                ->with('invoice.member')
                ->limit(5)
                ->get()
                ->map(function($payment) use ($user) {
                    if ($user->hasRole('super_admin')) {
                        $route = route('admin.finance.payments') . '?search=' . urlencode($payment->reference_number ?? '');
                    } else {
                        $route = route('finance.payments.show', $payment->id);
                    }
                    
                    return [
                        'id' => $payment->id,
                        'name' => $payment->reference_number ?? 'Payment #' . $payment->id,
                        'type' => 'Payment',
                        'url' => $route,
                        'description' => $payment->invoice->member->name ?? 'N/A',
                    ];
                });
        }

        // Search Events
        $eventQuery = Event::where('name', 'like', "%{$query}%");
        
        if ($user->hasRole(['owner', 'finance', 'coach'])) {
            $dojoId = currentDojo();
            $eventQuery->where('dojo_id', $dojoId);
        }

        $results['events'] = $eventQuery
            ->limit(5)
            ->get()
            ->map(function($event) use ($user) {
                $route = $user->hasRole('super_admin')
                    ? '#'
                    : route('owner.events.show', $event->id);
                
                return [
                    'id' => $event->id,
                    'name' => $event->name,
                    'type' => 'Event',
                    'url' => $route,
                    'description' => $event->dojo->name ?? 'N/A',
                ];
            });

        // Search Instructors
        if ($user->hasRole(['super_admin', 'owner', 'coach'])) {
            $instructorQuery = Instructor::where('name', 'like', "%{$query}%");
            
            if ($user->hasRole(['owner', 'coach'])) {
                $dojoId = currentDojo();
                $instructorQuery->where('dojo_id', $dojoId);
            }

            $results['instructors'] = $instructorQuery
                ->limit(5)
                ->get()
                ->map(function($instructor) use ($user) {
                    if ($user->hasRole('super_admin')) {
                        $route = route('admin.instructors.index') . '?search=' . urlencode($instructor->name);
                    } else {
                        $route = route('owner.instructors.show', $instructor->id);
                    }
                    
                    return [
                        'id' => $instructor->id,
                        'name' => $instructor->name,
                        'type' => 'Instructor',
                        'url' => $route,
                        'description' => $instructor->specialization ?? 'N/A',
                    ];
                });
        }

        // Search Classes
        if ($user->hasRole(['super_admin', 'owner', 'coach', 'student'])) {
            $classQuery = DojoClass::where('name', 'like', "%{$query}%");
            
            if ($user->hasRole(['owner', 'coach', 'student'])) {
                $dojoId = currentDojo();
                $classQuery->where('dojo_id', $dojoId);
            }

            $results['classes'] = $classQuery
                ->limit(5)
                ->get()
                ->map(function($class) {
                    return [
                        'id' => $class->id,
                        'name' => $class->name,
                        'type' => 'Class',
                        'url' => route('owner.classes.show', $class->id),
                        'description' => $class->dojo->name ?? 'N/A',
                    ];
                });
        }

        // Search Dojos (Admin only)
        if ($user->hasRole('super_admin')) {
            $results['dojos'] = Dojo::where('name', 'like', "%{$query}%")
                ->limit(5)
                ->get()
                ->map(function($dojo) {
                    return [
                        'id' => $dojo->id,
                        'name' => $dojo->name,
                        'type' => 'Dojo',
                        'url' => route('admin.dojos.show', $dojo->id),
                        'description' => $dojo->address ?? 'N/A',
                    ];
                });
        }

        // Combine all results
        $allResults = collect($results)->flatten(1)->take(10)->values();

        return response()->json([
            'results' => $allResults,
            'query' => $query
        ]);
    }
}

