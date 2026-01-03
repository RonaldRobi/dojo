<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ComingSoonController extends Controller
{
    public function index(Request $request, $page = null)
    {
        // Get page title from route parameter or request defaults
        $pageTitle = $page ?? $request->route('page') ?? $request->route()->defaults['page'] ?? 'Coming Soon';
        
        // Clean up the title
        if ($pageTitle !== 'Coming Soon') {
            $pageTitle = ucwords(str_replace(['-', '_'], ' ', $pageTitle));
        }
        
        return view('admin.coming-soon', compact('pageTitle'));
    }
}

