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
        // Landing page data
        $dojos = Dojo::with('profile')->whereHas('profile')->get();
        
        return view('public.index', compact('dojos'));
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
