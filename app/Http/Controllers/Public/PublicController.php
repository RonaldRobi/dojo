<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Dojo;
use App\Models\DojoProfile;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        // Get all active dojos for display
        $dojos = Dojo::orderBy('name')->get(['id', 'name', 'address', 'description']);
        
        // Get upcoming events (if events table exists)
        $upcomingEvents = [];
        if (class_exists(\App\Models\Event::class)) {
            $upcomingEvents = \App\Models\Event::where('event_date', '>=', now())
                ->orderBy('event_date')
                ->limit(3)
                ->get();
        }
        
        return view('public.index', compact('dojos', 'upcomingEvents'));
    }

    public function showDojo(Dojo $dojo)
    {
        $dojo->load(['profile', 'instructors', 'achievements']);
        
        if (request()->wantsJson()) {
            return response()->json($dojo);
        }
        
        return view('public.dojo', compact('dojo'));
    }

    public function dojoProfile(Dojo $dojo)
    {
        $profile = $dojo->profile;
        $instructors = $dojo->instructors()->where('status', 'active')->get();
        $achievements = $dojo->achievements()->orderBy('achieved_date', 'desc')->get();

        if (request()->wantsJson()) {
            return response()->json([
                'dojo' => $dojo,
                'profile' => $profile,
                'instructors' => $instructors,
                'achievements' => $achievements,
            ]);
        }
        
        return view('public.dojo', compact('dojo', 'profile', 'instructors', 'achievements'));
    }
}
