<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\EventCertificate;
use App\Models\Dojo;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventManagementController extends Controller
{
    // Event Nasional
    public function national(Request $request)
    {
        $query = Event::with(['dojo'])
            ->where('is_public', true)
            ->orWhereNull('dojo_id');

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $events = $query->orderBy('event_date', 'desc')->paginate(20);
        $dojos = Dojo::all();

        return view('admin.events.national', compact('events', 'dojos'));
    }

    // Turnamen Nasional
    public function tournaments(Request $request)
    {
        $query = Event::with(['dojo'])
            ->where('type', 'tournament')
            ->where(function($q) {
                $q->where('is_public', true)->orWhereNull('dojo_id');
            });

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $events = $query->orderBy('event_date', 'desc')->paginate(20);
        $dojos = Dojo::all();

        return view('admin.events.tournaments', compact('events', 'dojos'));
    }

    // Grading Nasional
    public function grading(Request $request)
    {
        $query = Event::with(['dojo'])
            ->where('type', 'grading')
            ->where(function($q) {
                $q->where('is_public', true)->orWhereNull('dojo_id');
            });

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $events = $query->orderBy('event_date', 'desc')->paginate(20);
        $dojos = Dojo::all();

        return view('admin.events.grading', compact('events', 'dojos'));
    }

    // Sertifikat Digital Global
    public function certificates(Request $request)
    {
        $query = EventCertificate::with(['eventRegistration.event.dojo', 'eventRegistration.member.dojo']);

        if ($request->has('event_id')) {
            $query->whereHas('eventRegistration', function($q) use ($request) {
                $q->where('event_id', $request->event_id);
            });
        }

        if ($request->has('dojo_id')) {
            $query->whereHas('eventRegistration.event', function($q) use ($request) {
                $q->where('dojo_id', $request->dojo_id);
            });
        }

        $certificates = $query->orderBy('issued_date', 'desc')->paginate(20);
        $events = Event::with('dojo')->get();
        $dojos = Dojo::all();

        return view('admin.events.certificates', compact('certificates', 'events', 'dojos'));
    }

    // Riwayat Event
    public function history(Request $request)
    {
        $query = Event::with(['dojo']);

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('dojo_id')) {
            $query->where('dojo_id', $request->dojo_id);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('date_from')) {
            $query->whereDate('event_date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('event_date', '<=', $request->date_to);
        }

        $events = $query->orderBy('event_date', 'desc')->paginate(20);
        $dojos = Dojo::all();

        return view('admin.events.history', compact('events', 'dojos'));
    }
}
